<?php
/**
*
* Coupon View
*
* @package	VirtueMart
* @subpackage Coupon
* @author RickG
 * @author Valerie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 10332 2020-06-16 16:10:39Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for maintaining the list of Coupons
 *
 * @package	VirtueMart
 * @subpackage Coupon
 * @author RickG
 * @author Valerie Isaksen
 */


class VirtuemartViewCoupon extends VmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		$model = VmModel::getModel();

		$layoutName = vRequest::getCmd('layout', 'default');


// 		if(Vmconfig::get('multix','none')!=='none'){
// 				$vendorList= ShopFunctions::renderVendorList($coupon->virtuemart_vendor_id);
// 				$this->assignRef('vendorList', $vendorList);
// 		}

		$vendorModel = VmModel::getModel('Vendor');
		$vendorModel->setId(1);
		$vendor = $vendorModel->getVendor();

		$currencyModel = VmModel::getModel('Currency');
		$currencyModel = $currencyModel->getCurrency($vendor->vendor_currency);
		$this->assignRef('vendor_currency', $currencyModel->currency_symbol);

		if ($layoutName == 'edit') {
			$coupon = $model->getCoupon();
			$this->SetViewTitle('', $coupon->coupon_code);
			if ($coupon->virtuemart_coupon_id < 1) {
				// Set a default expiration date
				$_expTime = explode(',', VmConfig::get('coupons_default_expire','14,D'));

				if (!empty( $_expTime[1]) && $_expTime[1] == 'W') {
					$_expTime[0] = $_expTime[0] * 7;
					$_expTime[1] = 'D';
				}
				if (version_compare(PHP_VERSION, '5.3.0', '<')) {
					$_dtArray = getdate(time());
					if ($_expTime[1] == 'D') {
						$_dtArray['mday'] += $_expTime[0];
					} elseif ($_expTime[1] == 'M') {
						$_dtArray['mon'] += $_expTime[0];
					} elseif ($_expTime[1] == 'Y') {
						$_dtArray['year'] += $_expTime[0];
					}
					$coupon->coupon_expiry_date =
						  mktime($_dtArray['hours'], $_dtArray['minutes'], $_dtArray['seconds']
						, $_dtArray['mon'], $_dtArray['mday'], $_dtArray['year']);
				} else {
					$_expDate = new DateTime();
					$_expDate->add(new DateInterval('P'.$_expTime[0].$_expTime[1]));
					$coupon->coupon_expiry_date = $_expDate->format("Y-m-d H:i:s");
				}
			}
			
			$db = JFactory::getDbo();
			
			// Shopper info
			if(empty($attrs['class'])) $attrs['class'] = 'vm-chzn-select vm-drop';
			$attrs['multiple'] = 'multiple';
			
			//Shopper Groups
			$options   = array();
			
			$query = $db->getQuery(true);
			$query
				->select(array('vms.virtuemart_shoppergroup_id as value', 'vms.shopper_group_name as text'))
				->from($db->quoteName('#__virtuemart_shoppergroups', 'vms'))
				->where('vms.virtuemart_shoppergroup_id NOT IN (1,2)')
				->order($db->quoteName('vms.virtuemart_shoppergroup_id') . ' ASC');
			$db->setQuery($query);
			$options = array_merge($options, $db->loadObjectList());
			
			$this->lists['shoppergroups'] = JHtml::_('select.genericlist', $options, 'virtuemart_shoppergroup_ids[]', $attrs, 'value', 'text', explode(',',$coupon->virtuemart_shoppergroup_ids), 'virtuemart_shoppergroup_ids');
			
			//VM Shopper
			$options   = array();
			
			$query = $db->getQuery(true);
			$query
				->select(array('vmu.virtuemart_user_id as value', 'us.name as text'))
				->from($db->quoteName('#__virtuemart_vmusers', 'vmu'))
				->join('LEFT', $db->quoteName('#__users', 'us') . ' ON (' . $db->quoteName('vmu.virtuemart_user_id') . ' = ' . $db->quoteName('us.id') . ')')
				->order($db->quoteName('us.name') . ' ASC');
			$db->setQuery($query);
			$options = array_merge($options, $db->loadObjectList());
			
			$this->lists['vmusers'] = JHtml::_('select.genericlist', $options, 'virtuemart_shopper_ids[]', $attrs, 'value', 'text', explode(',',$coupon->virtuemart_shopper_ids), 'virtuemart_shopper_ids');
			
			//VM Products
			$options   = array();
			
			$query = $db->getQuery(true);
			$query
				->select(array('vmp.virtuemart_product_id as value', 'vmpegb.product_name as text'))
				->from($db->quoteName('#__virtuemart_products', 'vmp'))
				->join('LEFT', $db->quoteName('#__virtuemart_products_en_gb', 'vmpegb') . ' ON (' . $db->quoteName('vmp.virtuemart_product_id') . ' = ' . $db->quoteName('vmpegb.virtuemart_product_id') . ')')
				->where($db->quoteName('vmp.published') . ' = 1')
				->order($db->quoteName('vmpegb.product_name') . ' ASC');
			$db->setQuery($query);
			$options = array_merge($options, $db->loadObjectList());
			
			$this->lists['products'] = JHtml::_('select.genericlist', $options, 'virtuemart_product_ids[]', $attrs, 'value', 'text', explode(',',$coupon->virtuemart_product_ids), 'virtuemart_product_ids');
			
			//VM Product Categories
			$options   = array();
			
			$query = $db->getQuery(true);
			$query
				->select(array('vmc.virtuemart_category_id as value', 'vceg.category_name as text'))
				->from($db->quoteName('#__virtuemart_categories', 'vmc'))
				->join('LEFT', $db->quoteName('#__virtuemart_categories_en_gb', 'vceg') . ' ON (' . $db->quoteName('vmc.virtuemart_category_id') . ' = ' . $db->quoteName('vceg.virtuemart_category_id') . ')')
				->where($db->quoteName('vmc.published') . ' = 1');
			$db->setQuery($query);
			$options = array_merge($options, $db->loadObjectList());
			
			$this->lists['productcategories'] = JHtml::_('select.genericlist', $options, 'virtuemart_category_ids[]', $attrs, 'value', 'text', explode(',',$coupon->virtuemart_category_ids), 'virtuemart_category_ids');
			
			$this->assignRef('coupon',	$coupon);

			$this->addStandardEditViewCommands();
			if($this->showVendors()){
				$this->vendorList = Shopfunctions::renderVendorList($coupon->virtuemart_vendor_id);
			}
		}

		if ($layoutName == 'couponsdata') {

			$this->coupons_data = $model->getCouponsData();
			$this->pagination = $model->getPagination();
			if($this->showVendors()){
				$this->vendorList = Shopfunctions::renderVendorList(vmAccess::getVendorId(), 'virtuemart_vendor_id', true);
			}
		} else {
			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model);

			$filter_coupon = vRequest::getString('filter_coupon', false);
			$this->coupons = $model->getCoupons($filter_coupon);

			$this->pagination = $model->getPagination();
			if($this->showVendors()){
				$this->vendorList = Shopfunctions::renderVendorList($model->virtuemart_vendor_id, 'virtuemart_vendor_id', true);
			}
			$this->SetViewTitle('COUPON');
		}

		parent::display($tpl);
	}

}
// pure php no closing tag
