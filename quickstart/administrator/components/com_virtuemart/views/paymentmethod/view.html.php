<?php
/**
 *
 * Calc View
 *
 * @package	VirtueMart
 * @subpackage Payment Method
 * @author Max Milbers
 * @author valérie isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 10152 2019-09-19 14:40:28Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Description
 *
 * @package		VirtueMart
 * @author valérie isaksen
 */

class VirtuemartViewPaymentMethod extends VmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		//$this->addHelperPath(VMPATH_ADMIN.DS.'helpers');

		$this->user = JFactory::getUser();
		$model = VmModel::getModel('paymentmethod');

		// TODO logo
		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');

		$vendorModel = VmModel::getModel('vendor');

		$vendorModel->setId(1);
		$vendor = $vendorModel->getVendor();
		$currencyModel = VmModel::getModel('currency');
		$currencyModel = $currencyModel->getCurrency($vendor->vendor_currency);
		$this->assignRef('vendor_currency', $currencyModel->currency_symbol);

		if ($layoutName == 'edit') {

			vmLanguage::loadJLang('plg_vmpsplugin', false);

			JForm::addFieldPath(VMPATH_ADMIN .'/fields');

			$payment = $model->getPayment();

			$this->checkConditionsCore = false;

			// Get the payment XML.
			$formFile	= vRequest::filterPath( VMPATH_ROOT .'/plugins/vmpayment/'. $payment->payment_element .'/'. $payment->payment_element . '.xml');
			if (file_exists($formFile)){

				$payment->form = JForm::getInstance($payment->payment_element, $formFile, array(),false, '//vmconfig | //config[not(//vmconfig)]');
				$payment->params = new stdClass();
				$varsToPush = vmPlugin::getVarsToPushFromForm($payment->form);

				VmTable::bindParameterableToSubField($payment,$varsToPush);
				$payment->form->bind($payment->getProperties());

				$fdata = $payment->form->getData()->toArray();
				if(isset($fdata['checkConditionsCore']) or isset($fdata['params']['checkConditionsCore'])){
					$this->checkConditionsCore = true;
					vmPSPlugin::addVarsToPushCore($varsToPush);
					VmTable::bindParameterableToSubField($payment,$varsToPush);

					$toRemove = array();
					vmPSPlugin::addVarsToPushCore($toRemove,1);
					foreach($toRemove as $name=>$v){
						$payment->form->removeField($name,'params');
					}
					$payment->form->bind($payment->getProperties());
					$this->shipmentList = shopfunctions::renderShipmentDropdown($payment->virtuemart_shipmentmethod_ids);
				}

			} else {
				$payment->form = null;
			}

			/*$this->checkConditionsCore = false;
			$fdata = $payment->form->getData()->toArray();
			//vmdebug('$this->checkConditionsCore = true',$fdata);
			if(isset($fdata['params']['checkConditionsCore'])){
				//$this->checkConditionsCore = true;
				$this->shipmentList = $this->renderShipmentDropdown($payment->virtuemart_shipment_ids);
				vmdebug('$this->checkConditionsCore = true');
			}*/



			$this->assignRef('payment',	$payment);
			$this->vmPPaymentList = self::renderInstalledPaymentPlugins($payment->payment_jplugin_id);
			$this->shopperGroupList = ShopFunctions::renderShopperGroupList($payment->virtuemart_shoppergroup_ids, true);

			if($this->showVendors()){
				$this->vendorList= ShopFunctions::renderVendorList($payment->virtuemart_vendor_id);
			}

			$currency_model = VmModel::getModel ('currency');
			$currencies = $currency_model->getCurrencies ();

			$currency = VirtueMartModelVendor::getVendorCurrency ($payment->virtuemart_vendor_id);
			$this->assignRef('vendor_currency', $currency->currency_symbol);

			if(empty($payment->currency_id)) $payment->currency_id = $currency->virtuemart_currency_id;
			$attrs['class'] = 'vm-chzn-select vm-drop';
			$this->currencyList = JHtml::_ ('select.genericlist', $currencies, 'currency_id', $attrs, 'virtuemart_currency_id', 'currency_name', $payment->currency_id);

			$this->addStandardEditViewCommands( $payment->virtuemart_paymentmethod_id);
		} else {
			JToolbarHelper::custom('clonepayment', 'copy', 'copy', vmText::_('COM_VIRTUEMART_PAYMENT_CLONE'), true);

			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model);

			$this->payments = $model->getPayments();
			vmLanguage::loadJLang('com_virtuemart_shoppers',TRUE);

			foreach ($this->payments as &$data){
				// Write the first 5 shoppergroups in the list
				$data->paymShoppersList = shopfunctions::renderGuiList($data->virtuemart_shoppergroup_ids,'shoppergroups','shopper_group_name','shoppergroup' );
			}

			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

	function renderInstalledPaymentPlugins($selected){

		$db = JFactory::getDBO();

		$q = 'SELECT * FROM `#__extensions` WHERE `folder` = "vmpayment" and `state`="0"  ORDER BY `ordering`,`name` ASC';
		$db->setQuery($q);
		$result = $db->loadAssocList('extension_id');
		if(empty($result)){
			$app = JFactory::getApplication();
			$app -> enqueueMessage(vmText::_('COM_VIRTUEMART_NO_PAYMENT_PLUGINS_INSTALLED'));
		}

		$listHTML='<select id="payment_jplugin_id" name="payment_jplugin_id" style= "width: 300px;">';

		foreach($result as $paym){
			if($paym['extension_id']==$selected) $checked='selected="selected"'; else $checked='';
			// Get plugin info
			$listHTML .= '<option '.$checked.' value="'.$paym['extension_id'].'">'.vmText::_($paym['name']).'</option>';

		}
		$listHTML .= '</select>';

		return $listHTML;
	}


}
// pure php not tag
