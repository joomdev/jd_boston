<?php
/**
*
* Data module for shop coupons
*
* @package	VirtueMart
* @subpackage Coupon
* @author Max Milbers
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2020 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Model class for shop coupons
 *
 * @package	VirtueMart
 * @subpackage Coupon
 */
class VirtueMartModelCoupon extends VmModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('coupons');
	}

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     */
	function getCoupon($id = 0){
		return $this->getData($id);
	}

	/**
	 * Bind the post data to the coupon table and save it
     *
     * @return mixed False if the save was unsuccessful, the coupon ID otherwise.
	 */
    function store(&$data) {
		if(!vmAccess::manager('coupon.edit')){
			vmWarn('Insufficient permission to store coupons');
			return false;
		} else if( empty($data['virtuemart_coupon_id']) and !vmAccess::manager('coupon.create')){
			vmWarn('Insufficient permission to create coupons');
			return false;
		}
		$table = $this->getTable('coupons');

		/* Changes Modified */
		$data['virtuemart_shopper_ids'] = implode(',',$data['virtuemart_shopper_ids']);
		$data['virtuemart_shoppergroup_ids'] = implode(',',$data['virtuemart_shoppergroup_ids']);
		$data['virtuemart_product_ids'] = implode(',',$data['virtuemart_product_ids']);
		$data['virtuemart_category_ids'] = implode(',',$data['virtuemart_category_ids']);

		// Convert selected dates to MySQL format for storing.
		if ($data['coupon_start_date']) {
		    $startDate = JFactory::getDate($data['coupon_start_date']);
		    $data['coupon_start_date'] = $startDate->toSQL();
		}
		if ($data['coupon_expiry_date']) {
		    $expireDate = JFactory::getDate($data['coupon_expiry_date']);
		    $data['coupon_expiry_date'] = $expireDate->toSQL();
		}
		$table->bindChecknStore($data);
		$data['virtuemart_coupon_id'] = $table->virtuemart_coupon_id;

        return $table->virtuemart_coupon_id;
	}


	/**
	 * Retireve a list of coupons from the database.
	 *
	 * @return object List of coupon objects
	 */
	function getCoupons($filterCoupon = false) {

		$this->virtuemart_vendor_id = vmAccess::getVendorId();
		$where = array();

		if(!empty($this->virtuemart_vendor_id)){
			$where[] = '`virtuemart_vendor_id`="'.$this->virtuemart_vendor_id.'"';
		}
		if($filterCoupon) {

			$filterCouponS = '"%' . $this->_db->escape( $filterCoupon, true ) . '%"' ;
			$where[] = '`coupon_code` LIKE '.$filterCouponS;

		}

		$whereString = '';
		if (count($where) > 0) $whereString = ' WHERE '.implode(' AND ', $where) ;

		return $this->_data = $this->exeSortSearchListQuery(0,'*',' FROM `#__virtuemart_coupons`',$whereString,'',$this->_getOrdering());
	}

	/* Changes Modified *
	function getVmUsers() {
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select(array('vmu.*', 'us.id', 'us.username', 'us.name', 'us.email'))
			->from($db->quoteName('#__virtuemart_vmusers', 'vmu'))
			->join('INNER', $db->quoteName('#__users', 'us') . ' ON (' . $db->quoteName('vmu.virtuemart_user_id') . ' = ' . $db->quoteName('us.id') . ')')
			->order($db->quoteName('us.name') . ' ASC');

		$results = $this->exeSortSearchListQuery(0,'vmu.*, us.id, us.username, us.name, us.email',' FROM `#__virtuemart_vmusers` as vmu ','INNER JOIN '. $db->quoteName('#__users', 'us') . ' ON (' . $db->quoteName('vmu.virtuemart_user_id') . ' = ' . $db->quoteName('us.id') . ')','',$this->_getOrdering());
		//$db->setQuery($query);

		//$results = $db->loadObjectList();
		vmdebug('used?');
		return $results;
	}*/
	
	/* Changes Modified */
	function getCouponsData() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$filter_coupon = vRequest::getVar('filter_coupon', '');
		$filter_shopper = vRequest::getVar('filter_shopper', '');
		$filter_order_number = vRequest::getVar('filter_order_number', '');
		$filter_from_date = vRequest::getVar('filter_from_date', '');
		$filter_to_date = vRequest::getVar('filter_to_date', '');


		$limitStart = $this->_limitStart;
		$limit = $this->_limit;

		$query
			->select(array('SQL_CALC_FOUND_ROWS vo.virtuemart_order_id', 'vo.virtuemart_user_id', 'vo.virtuemart_vendor_id', 'vo.order_number', 'vo.order_total', 'vo.created_on', 'vo.coupon_discount', 'vo.customer_number', 'vc.coupon_code', 'vu.name'))
			->from($db->quoteName('#__virtuemart_orders', 'vo'))
			->join('RIGHT', $db->quoteName('#__virtuemart_coupons', 'vc') . ' ON (' . $db->quoteName('vo.coupon_code') . ' = ' . $db->quoteName('vc.coupon_code') . ')')
			->join('LEFT', $db->quoteName('#__users', 'vu') . ' ON (' . $db->quoteName('vo.virtuemart_user_id') . ' = ' . $db->quoteName('vu.id') . ')');
			$query->where('(vo.virtuemart_order_id != "" OR virtuemart_order_id != NULL)');
			
			if($filter_coupon){
				$query->where('vc.coupon_code LIKE "%'.$filter_coupon.'%"');
			}
			if($filter_shopper){
				$query->where('vu.name LIKE "%'.$filter_shopper.'%"');
			}
			if($filter_order_number){
				$query->where('vo.order_number LIKE "%'.$filter_order_number.'%"');
			}
			if($filter_from_date && $filter_to_date){
				$query->where('(vo.created_on between CAST("'.$filter_from_date.'"  AS DATE) AND CAST("'.$filter_to_date.'" AS DATE))');
			}
			
			$query->order($db->quoteName('vo.created_on') . ' DESC');

		if(empty($this->_limit)) $this->setPaginationLimits();
		$db->setQuery($query, $this->_limitStart, $this->_limit);

		$results = $db->loadObjectList();

		$db->setQuery('SELECT FOUND_ROWS()');
		$count = $db->loadResult();

		if($count == false){
			$count = 0;
		}
		$this->_total = $count;

		return $results;
	}

	function remove($ids){
		if(!vmAccess::manager('coupon.delete')){
			vmWarn('Insufficient permissions to remove state');
			return false;
		}
		return parent::remove($ids);
	}
}

// pure php no closing tag