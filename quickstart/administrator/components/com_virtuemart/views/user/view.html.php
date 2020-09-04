<?php
/**
 *
 * List/add/edit/remove Users
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 10203 2019-11-18 11:06:13Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for maintaining the list of users
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 */
class VirtuemartViewUser extends VmViewAdmin {

	function display($tpl = null) {

		$model = VmModel::getModel();
		$currentUser = JFactory::getUser();

		vmLanguage::loadJLang('com_virtuemart_shoppers',TRUE);

		$task = vRequest::getCmd('task', 'edit');
		$isSuperOrVendor = vmAccess::isSuperVendor();
		if($task == 'editshop'){

			if(empty($isSuperOrVendor)){
				JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
			} else {
				$userId = VirtueMartModelVendor::getUserIdByVendorId($isSuperOrVendor);
			}
			$this->SetViewTitle('STORE'  );
		} else if ($task == 'add'){
			$userId  = 0;
		} else {
			$userId = vRequest::getVar('virtuemart_user_id',0);
			if(is_array($userId)){
				$userId = $userId[0];
			}
			$this->SetViewTitle('USER');
		}
		$userId = $model->setId($userId);

		//$layoutName = vRequest::getCmd('layout', 'default');
		$layoutName = $this->getLayout();

		if ($layoutName == 'edit' || $layoutName == 'edit_shipto') {

			$editor = JFactory::getEditor();

			$userDetails = $model->getUser();

			if($task == 'editshop' && $userDetails->user_is_vendor){
// 				$model->setCurrent();
				if(!empty($userDetails->vendor->vendor_store_name)){
					$this->SetViewTitle('STORE',$userDetails->vendor->vendor_store_name, 'shop_mart' );
				} else {
					$this->SetViewTitle('STORE',vmText::_('COM_VIRTUEMART_NEW_VENDOR') , 'shop_mart');
				}
				$vendorid = $userDetails->virtuemart_vendor_id;
				if($vendorid==1)$this -> checkTCPDFinstalled();
			} else {
				$vendorid = 0 ;
				$this->SetViewTitle('USER',$userDetails->JUser->get('name'));
			}

			$_new = ($userDetails->JUser->get('id') < 1);

			$this->addStandardEditViewCommands($vendorid);

			// User details
			$_contactDetails = $model->getContactDetails();

			$this->lists['canBlock'] = ($currentUser->authorise('com_users', 'block user')
			&& ($userDetails->JUser->get('id') != $currentUser->get('id'))); // Can't block myself
			$this->lists['canSetMailopt'] = $currentUser->authorise('workflow', 'email_events');
			$this->lists['block'] = JHtml::_('select.booleanlist', 'block',      'class="inputbox"', $userDetails->JUser->get('block'),     'COM_VIRTUEMART_YES', 'COM_VIRTUEMART_NO');
			$this->lists['sendEmail'] = JHtml::_('select.booleanlist', 'sendEmail',  'class="inputbox"', $userDetails->JUser->get('sendEmail'), 'COM_VIRTUEMART_YES', 'COM_VIRTUEMART_NO');
			$this->lists['params'] = $userDetails->JUser->getParameters(true);

			// Shopper info
			$this->lists['shoppergroups'] = ShopFunctions::renderShopperGroupList($userDetails->shopper_groups,true, 'virtuemart_shoppergroup_id');
			$this->lists['vendors'] = '';
			if($this->showVendors()){
				$this->lists['vendors'] = ShopFunctions::renderVendorList($userDetails->virtuemart_vendor_id, 'virtuemart_vendor_id', false);
			}

			$isSuper = vmAccess::isSuperVendor($userDetails->JUser->get('id'),'none');

			if(VmConfig::get('multixcart',0)=='byvendor' and $isSuper==0){

				$vUser = $model->getTable('vendor_users');
				$vUser->load($userDetails->JUser->get('id'));
				$userDetails->virtuemart_vendor_user_id = $vUser->virtuemart_vendor_user_id;


				$this->lists['vendor'] = ShopFunctions::renderVendorList($userDetails->virtuemart_vendor_user_id, 'virtuemart_vendor_user_id', false, true);
			}

			$model->setId($userDetails->JUser->get('id'));
			$this->lists['custnumber'] = $model->getCustomerNumberById();

			// Shipment address(es)
			$this->lists['shipTo'] = shopFunctionsF::generateStAddressList($this, $model, 'addST');

			$new = false;
			if(vRequest::getInt('new','0')==1){
				$new = true;
			}

			$virtuemart_userinfo_id_BT = $model->getBTuserinfo_id($userId);
			$userFieldsArray = $model->getUserInfoInUserFields($layoutName,'BT',$virtuemart_userinfo_id_BT,false);
			$userFieldsBT = $userFieldsArray[$virtuemart_userinfo_id_BT];

			// Load the required scripts
			if (count($userFieldsBT['scripts']) > 0) {
				foreach ($userFieldsBT['scripts'] as $_script => $_path) {
					JHtml::script($_script, $_path);
				}
			}
			// Load the required stylesheets
			if (count($userFieldsBT['links']) > 0) {
				foreach ($userFieldsBT['links'] as $_link => $_path) {
					vmJsApi::css($_link, $_path);
				}
			}

			$this->assignRef('userFieldsBT', $userFieldsBT);
			$this->assignRef('userInfoID', $virtuemart_userinfo_id_BT);


			$addrtype = vRequest::getCmd('addrtype');
			$virtuemart_userinfo_id = 0;
			if ($layoutName == 'edit_shipto' or $task=='addST' or $addrtype=='ST') {
				$virtuemart_userinfo_id = vRequest::getString('virtuemart_userinfo_id', '0','');
				$userFieldsArray = $model->getUserInfoInUserFields($layoutName,'ST',$virtuemart_userinfo_id,false);
				if($new ){
					$virtuemart_userinfo_id = 0;

				} else {

				}
				$userFieldsST = $userFieldsArray[$virtuemart_userinfo_id];
				$this->assignRef('shipToFields', $userFieldsST);
			}

			$this->assignRef('shipToId', $virtuemart_userinfo_id);
			$this->assignRef('new', $new);

			if (!$_new) {
				// Check for existing orders for this user
				$orders = VmModel::getModel('orders');
				$orderList = $orders->getOrdersList($userDetails->JUser->get('id'), true);
			} else {
				$orderList = null;
			}


			if (count($orderList) > 0 || !empty($userDetails->user_is_vendor)) {
				$currency = CurrencyDisplay::getInstance();
				$this->assignRef('currency',$currency);
			}

			if (!empty($userDetails->user_is_vendor)) {


				$vendorM = VmModel::getModel('vendor');
				//if(empty($userDetails->vendor->vendor_currency)){
					$vendorCurrency = $vendorM->getVendorCurrency(1);
					if($vendorCurrency) {
						$userDetails->vendor->vendor_currency = $vendorCurrency->vendor_currency;
						vmdebug('No vendor currency given, fallback to main vendor',$userDetails->vendor->vendor_currency);
					}
				//}
				$vendorM->setId($userDetails->virtuemart_vendor_id);

				$vendorM->addImages($userDetails->vendor);
				$this->assignRef('vendor', $userDetails->vendor);

				$currencyModel = VmModel::getModel('currency');
				$_currencies = $currencyModel->getCurrencies();
				$this->assignRef('currencies', $_currencies);
				
				$configModel = VmModel::getModel('config');
				$TCPDFFontsList = $configModel->getTCPDFFontsList();
				$this->assignRef('pdfFonts', $TCPDFFontsList);

			}


			$this->assignRef('userDetails', $userDetails);

			$this->assignRef('orderlist', $orderList);
			$this->assignRef('contactDetails', $_contactDetails);
			$this->assignRef('editor', $editor);

		} else {

			JToolbarHelper::divider();
			if($this->showVendors()){
				JToolbarHelper::custom('toggle.user_is_vendor.1', 'publish','','COM_VIRTUEMART_USER_ISVENDOR');
				JToolbarHelper::custom('toggle.user_is_vendor.0', 'unpublish','','COM_VIRTUEMART_USER_ISNOTVENDOR');
				JToolbarHelper::divider();
			}

			if (vmAccess::manager('user.delete')) {
				JToolbarHelper::deleteList();
			}

			JToolbarHelper::editList();
			self::showACLPref('user');
			//This is intentionally, creating new user via BE is buggy and can be done by joomla
			//JToolbarHelper::addNewX();
			$this->addStandardDefaultViewLists($model,'ju.id');

			$orders = VmModel::getModel('orders');
			$this->userList = $model->getUserList();
			foreach($this->userList as $i=>$user){

				$this->userList[$i]->orderCount = $orders->getOrderCount($user->id);
				$this->userList[$i]->shoppergroups = VirtueMartModelShopperGroup::getShoppergroupById($user->id);
			}
			$this->pagination = $model->getPagination();

			$shoppergroupmodel = VmModel::getModel('shopperGroup');
			$this->defaultShopperGroup = $shoppergroupmodel->getDefault(0)->shopper_group_name;

			$searchOptionTables = array(
			'0' => array('searchTable' => 'juser', 'searchTable_name' => vmText::_('COM_VIRTUEMART_ONLY_JUSER')),
			'1' => array('searchTable' => 'all', 'searchTable_name' => vmText::_('JALL'))
			);
			$this->vendors = '';
			if($this->showVendors()){
				$searchOptionTables[] = array('searchTable' => 'vendors', 'searchTable_name' => vmText::_('COM_VM_ONLY_VENDORS'));
				$searchOptionTables[] = array('searchTable' => 'shoppers', 'searchTable_name' => vmText::_('COM_VM_ONLY_SHOPPERS'));
				$vendorId = vRequest::getInt('virtuemart_vendor_id', vmAccess::isSuperVendor());
				$this->vendors = Shopfunctions::renderVendorList($vendorId, 'virtuemart_vendor_id', true);
			}
			$this->searchOptions = JHtml::_('Select.genericlist', $searchOptionTables, 'searchTable', '', 'searchTable', 'searchTable_name', $model->searchTable );
		}


		if(!empty($this->orderlist)){
			vmLanguage::loadJLang('com_virtuemart_orders',TRUE);
		}
		parent::display($tpl);
	}

	/*
	*	What is this doing here?
	*
	*/

	function renderMailLayout ($doVendor=false) {
		$tpl = ($doVendor) ? 'mail_html_regvendor' : 'mail_html_reguser';
		$this->setLayout($tpl);

		$vendorModel = VmModel::getModel('vendor');
		$vendorId = 1;
		$vendorModel->setId($vendorId);
		$vendor = $vendorModel->getVendor();
		$vendorModel->addImages($vendor);
		$this->assignRef('subject', ($doVendor) ? vmText::sprintf('COM_VIRTUEMART_NEW_USER_MESSAGE_VENDOR_SUBJECT', $this->user->get('email')) : vmText::sprintf('COM_VIRTUEMART_NEW_USER_MESSAGE_SUBJECT',$vendor->vendor_store_name));
		parent::display();
	}

	private function checkTCPDFinstalled(){
		return vmDefines::tcpdf();
	}

}

//No Closing Tag
