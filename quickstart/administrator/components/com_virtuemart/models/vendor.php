<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD, RickG
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: vendor.php 10221 2019-12-09 16:19:30Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

/**
 * Model for VirtueMart Vendors
 *
 * @package        VirtueMart
 */
class VirtueMartModelVendor extends VmModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 *
	 * @author Max Milbers
	 */
	function __construct () {

		parent::__construct ();

		//Todo multivendor nasty hack, to get vendor with id 1
		if (Vmconfig::get ('multix', 'none') == 'none') {
			$this->setId (1);
		}

		$this->setMainTable ('vendors');
	}

	/**
	 * name: getLoggedVendor
	 * Checks which $vendorId has the just logged in user.
	 * @deprecated use vmAccess::isSuperVendor instead
	 * @author Max Milbers
	 * @param @param $ownerOnly returns only an id if the vendorOwner is logged in (dont get confused with storeowner)
	 * returns int $vendorId
	 */
	static function getLoggedVendor ($ownerOnly = TRUE) {

		$user = JFactory::getUser ();
		$userId = $user->id;
		if (isset($userId)) {
			$vendorId = self::getVendorId ('user', $userId, $ownerOnly);
			return $vendorId;
		} else {
			//vmError ('$virtuemart_user_id empty, no user logged in', '$virtuemart_user_id empty, no user logged in');
			return 0;
		}

	}

	/**
	 * Retrieve the vendor details from the database.
	 *
	 * @author Max Milbers
	 * @return object Vendor details
	 */
	function getVendor ($vendor_id = 0) {

		if(!empty($vendor_id)) $this->_id = (int)$vendor_id;

		if (empty($this->_cache[$this->_id][VmConfig::$vmlang])) {

			$this->_cache[$this->_id][VmConfig::$vmlang] = $this->getTable ('vendors');
			$this->_cache[$this->_id][VmConfig::$vmlang]->load ($this->_id);
// 			vmdebug('getVendor',$this->_id,$this->_data);
			// Convert ; separated string into array
			if ($this->_cache[$this->_id][VmConfig::$vmlang]->vendor_accepted_currencies) {
				$this->_cache[$this->_id][VmConfig::$vmlang]->vendor_accepted_currencies = explode (',', $this->_cache[$this->_id][VmConfig::$vmlang]->vendor_accepted_currencies);
			} else {
				$this->_cache[$this->_id][VmConfig::$vmlang]->vendor_accepted_currencies = array();
			}

			//Todo, check this construction
			$xrefTable = $this->getTable ('vendor_medias');
			$this->_cache[$this->_id][VmConfig::$vmlang]->virtuemart_media_id = $xrefTable->load ($this->_id);

		}

		return $this->_cache[$this->_id][VmConfig::$vmlang];
	}

	/**
	 * Retrieve a list of vendors
	 * todo only names are needed here, maybe it should be enhanced (loading object list is slow)
	 * todo add possibility to load without limit
	 *
	 * @author Max Milbers
	 * @return object List of vendors
	 */
	public function getVendors ($keyword = "") {


		$this->setId (0); //This is important ! notice by Max Milbers
		//$query = 'SELECT * FROM `#__virtuemart_vendors_' . VmConfig::$vmlang . '` as l JOIN `#__virtuemart_vendors` as v using (`virtuemart_vendor_id`)';
//$this->setDebugSql(1);

		$langFields = array('vendor_store_desc','vendor_terms_of_service','vendor_legal_info','vendor_letter_css','vendor_letter_header_html','vendor_letter_footer_html', 'vendor_store_name', 'vendor_phone', 'vendor_url', 'metadesc', 'metakey', 'customtitle', 'vendor_invoice_free1', 'vendor_invoice_free2', 'vendor_mail_free1', 'vendor_mail_free2', 'vendor_mail_css', 'slug');
		$select = ' v.*, vm.virtuemart_media_id,'.implode(', ',self::joinLangSelectFields($langFields));
		$joins = ' FROM #__virtuemart_vendors as v '.implode(' ',self::joinLangTables('#__virtuemart_vendors','v','virtuemart_vendor_id')) .' LEFT JOIN #__virtuemart_vendor_medias as vm on v.virtuemart_vendor_id=vm.virtuemart_vendor_id' ;
		$whereString = '';
		$ordering = ' ORDER BY l.`virtuemart_vendor_id`';

		//$this->_data = $this->_getList ($query, $this->getState ('limitstart'), $this->getState ('limit'));vmdebug('my data',$this->_data);
		//return $this->_data;


		$hash = trim($keyword.'.'.VmLanguage::$currLangTag);
		if(!isset($vends[$hash])){
			$vends[$hash] = $this->exeSortSearchListQuery(0,$select,$joins,$whereString,'GROUP BY virtuemart_vendor_id',$ordering );
			$venTab = $this->getTable('vendors');
			foreach($vends[$hash] as $ven){
				if(!empty($venTab->_varsToPushParam)){
					VmTable::bindParameterable($ven,'vendor_params',$venTab->_varsToPushParam);
				}
			}
		}

		return $vends[$hash];
	}

	/**
	 * Find the user id given a vendor id
	 *
	 * @author Max Milbers
	 * @param int $virtuemart_vendor_id
	 * @return int $virtuemart_user_id
	 */
	static function getUserIdByVendorId ($vendorId) {

		static $uIds = array();
		if (empty($vendorId)) {
			return;
		} else if(isset($uIds[$vendorId])) {
			return $uIds[$vendorId];
		} else {
			$db = JFactory::getDBO ();
			$query = 'SELECT `virtuemart_user_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_vendor_id`=' . (int)$vendorId;
			$db->setQuery ($query);
			$result = $db->loadResult ();
			$err = $db->getErrorMsg ();
			if (!empty($err)) {
				$uIds[$vendorId] = 0;
				vmError ('getUserIdByVendorId ' . $err, 'Failed to retrieve user id by vendor');
			} else {
				$uIds[$vendorId] = (int)$result;
			}
			return $uIds[$vendorId];
		}
	}


	/**
	 * Bind the post data to the vendor table and save it
	 * This function DOES NOT safe information which is in the vmusers or vm_user_info table
	 * It only stores the stuff into the vendor table
	 *
	 * @author RickG
	 * @author Max Milbers
	 * @return boolean True is the save was successful, false otherwise.
	 */
	function store (&$data) {

		JPluginHelper::importPlugin ('vmvendor');	//plugin type for one function? Developers should use the vmshopper plugin instead
		JPluginHelper::importPlugin ('vmshopper');
		$dispatcher = JDispatcher::getInstance ();
		$plg_datas = $dispatcher->trigger ('plgVmOnVendorStore', $data);
		foreach ($plg_datas as $plg_data) {
			$data = array_merge ($plg_data);
		}

		//The language table is involved and checked first, so we cannot use the check function in the vendor table, so we must do the checks here.
		$givenVendorId = $data['virtuemart_vendor_id'];
		$table = $this->getTable ('vendors');
		$userTable = $this->getTable ('vmusers');
		if(!empty($givenVendorId) and !vmAccess::manager('managevendors')){
			$userId = self::getUserIdByVendorId($givenVendorId);

			$userTable->load($userId);
			if(!empty($userTable->virtuemart_vendor_id) and $userTable->virtuemart_vendor_id!=$givenVendorId){
				VmWarn('User does not fit to vendor, storing cancelled');
				return false;
			}
		}

		/*	if(!$table->checkDataContainsTableFields($data)){
		 $app = JFactory::getApplication();
		 //$app->enqueueMessage('Data contains no Info for vendor, storing not needed');
		 return $this->_id;
	 	}*/

		if(empty($data['vendor_name'])){
			$data['vendor_name'] = '';
			if(!empty($data['title'])){
				$data['vendor_name'] = $data['title'];
			}
			if(!empty($data['last_name'])){
				$data['vendor_name'] .= ' '.$data['last_name'];
			}
			if(!empty($data['company'])){
				$data['vendor_name'] .= ' '.$data['company'];
			}
			if(empty($data['vendor_name'])){

				if(!empty($data['zip'])){
					$data['vendor_name'] .= $data['zip'];
				}
				if(!empty($data['city'])){
					$data['vendor_name'] .= $data['city'];
				}
			}
		}
		if(empty($data['vendor_store_name'])){
			if(!empty($data['vendor_name'])){
				$data['vendor_store_name'] = $data['vendor_name'];
			} else if(!empty($data['company'])){
				$data['vendor_store_name'] = $data['company'];
			} else {
				$data['vendor_store_name'] = vmText::_('COM_VIRTUEMART_VENDOR').' '.$data['vendor_name'];
			}
		}
		if(empty($data['vendor_name'])) $data['vendor_name'] = $data['vendor_store_name'];

		if(!vmAccess::manager('managevendors')){
			if(empty($givenVendorId)){
				$data['max_cats_per_product'] = VmConfig::get('max_cats_per_product',-1);
				$data['max_products'] = VmConfig::get('max_products',-1);
				$data['max_customers'] = VmConfig::get('max_customers',-1);
				$data['force_product_pattern'] = VmConfig::get('force_product_pattern',-1);
			} else {
				$table->load($givenVendorId);
				$data['max_cats_per_product'] = $table->max_cats_per_product;
				$data['max_products'] = $table->max_products;
				$data['max_customers'] = $table->max_customers;
				$data['force_product_pattern'] = $table->force_product_pattern;
			}
		}

		$res = $table->bindChecknStore ($data);
		if(!$res) {
			vmError ('Error storing vendor');
		}

		//set vendormodel id to the lastinserted one
		if (empty($this->_id)) {
			$data['virtuemart_vendor_id'] = $this->_id = $table->virtuemart_vendor_id;
		} else {
			$data['virtuemart_vendor_id'] = $table->virtuemart_vendor_id;
		}

		if ($this->_id != $givenVendorId) {

			$multix = VmConfig::get('multix', 'none');
			if($multix == 'none'){
				vmdebug('Developer notice, tried to update vendor xref should not appear in singlestore $oldVendorId = '.$oldVendorId.' newId = '.$this->_id.' updating');
			}

			//update user table
			$userTable->load($data['virtuemart_user_id']);

			if(empty($userTable->virtuemart_vendor_id)){
				$userTable->virtuemart_vendor_id = $this->_id;
			} else if(!vmAccess::manager('core') and $userTable->virtuemart_vendor_id!=$this->_id){
				vmError('Vendor created, but table entry of the user has already another vendor id and got not updated. '.$userTable->virtuemart_vendor_id);
				return false;
			}

			$userTable->store();
		}
		// Process the images
		$mediaModel = VmModel::getModel ('Media');
		$mediaModel->storeMedia ($data, 'vendor');

		$plg_datas = $dispatcher->trigger ('plgVmAfterVendorStore', $data);
		foreach ($plg_datas as $plg_data) {
			$data = array_merge ($plg_data);
		}

		return $this->_id;

	}

	/**
	 * Get the vendor specific currency
	 *
	 * @author Oscar van Eijk
	 * @param $_vendorId Vendor ID
	 * @return string Currency code
	 */

	static $_vendorCurrencies = array();
	static function getVendorCurrency ($_vendorId) {
		if(empty($_vendorId)){
			vmTrace('getVendorCurrency vendor Id empty');
			$_vendorId = 1;
		}
		if(!isset(self::$_vendorCurrencies[$_vendorId])){
			$db = JFactory::getDBO ();
			if(VmConfig::get('anyInShopCurrency',true)){
				$q = 'SELECT *  FROM `#__virtuemart_currencies` AS c
			LEFT JOIN `#__virtuemart_vendors` AS v ON  c.virtuemart_currency_id = v.vendor_currency
			WHERE v.virtuemart_vendor_id = "' . (int)$_vendorId . '"';
				$db->setQuery ($q);
				self::$_vendorCurrencies[$_vendorId] = $db->loadObject ();
				if(!self::$_vendorCurrencies[$_vendorId]){
					$link = JURI::root(false).'administrator/index.php?option=com_virtuemart&view=user&task=editshop';
					vmWarn('COM_VIRTUEMART_CONF_WARN_NO_CURRENCY_DEFINED','<a href="'.$link.'">'.$link.'</a>');
				}
			} else {
				$virtuemart_currency_id = vRequest::get('virtuemart_currency_id',false);
				self::getVendorAndAcceptedCurrencies($_vendorId);
				if(in_array($virtuemart_currency_id, self::$_vendorAcceptedCurrencies)){
					$q = 'SELECT *  FROM `#__virtuemart_currencies` AS c
			LEFT JOIN `#__virtuemart_vendors` AS v ON  c.virtuemart_vendor_id = v.virtuemart_vendor_id
			WHERE v.virtuemart_currency_id = "' . (int)$virtuemart_currency_id . '"';
					$db->setQuery ($q);
					self::$_vendorCurrencies[$_vendorId] = $db->loadObject ();

				}
			}

		}

		return self::$_vendorCurrencies[$_vendorId];
	}

	static $_vendorAcceptedCurrencies = array();
	function getVendorAndAcceptedCurrencies($vendorId){

		if(!isset(self::$_vendorAcceptedCurrencies[$vendorId])){
			$db = JFactory::getDBO();
// the select list should include the vendor currency which is the currency in which the product prices are displayed by default.
			$q  = 'SELECT CONCAT(`vendor_accepted_currencies`, ",",`vendor_currency`) AS all_currencies, `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`='.(int)$vendorId;
			$db->setQuery($q);
			self::$_vendorAcceptedCurrencies[$vendorId] = $db->loadAssoc();
		}

		return self::$_vendorAcceptedCurrencies[$vendorId];
	}

	/**
	 * @deprecated
	 * @param $virtuemart_order_id
	 * @return int|mixed
	 */
	public function getUserIdByOrderId ($virtuemart_order_id) {

		if (empty ($virtuemart_order_id)) {
			return 0;
		}
		$virtuemart_order_id = (int)$virtuemart_order_id;
		$q = "SELECT `virtuemart_user_id` FROM `#__virtuemart_orders` WHERE `virtuemart_order_id`='.$virtuemart_order_id'";
		$db = JFactory::getDBO();
		$db->setQuery ($q);

//		if($db->next_record()){
		if ($db->execute ()) {
//			$virtuemart_user_id = $db->f('virtuemart_user_id');
			return $db->loadResult ();
		} else {
			vmError ('Error in DB; No virtuemart_user_id found for $virtuemart_order_id ' . $virtuemart_order_id );
			return 0;
		}
	}


	/**
	 * Gets the vendorId by user Id mapped by table auth_user_vendor or by the order item
	 * Assigned users cannot change storeinformations
	 * ownerOnly = false should be used for users who are assigned to a vendor
	 * for administrative jobs like execution of orders or managing products
	 * Changing of vendorinformation should ONLY be possible by the Mainvendor who is in charge
	 *
	 * @deprecated use vmAccess::isSuperVendor instead
	 * @author by Max Milbers
	 * @author RolandD
	 * @param string $type Where the vendor ID should be taken from
	 * @param mixed  $value Whatever value the vendor ID should be filtered on
	 * @return int Vendor ID
	 */
	static public function getVendorId ($type, $value, $ownerOnly = TRUE) {

		if (empty($value)) {
			return 0;
		}

		static $c = array();
		//sanitize input params
		$value = (int)$value;
		$h = $value.$type.$ownerOnly;
		if(isset($c[$h])){
			return $c[$h];
		}
		//static call used, so we need our own db instance
		$db = JFactory::getDBO ();
		switch ($type) {
			case 'order':
				$q = 'SELECT virtuemart_vendor_id FROM #__virtuemart_order_items WHERE virtuemart_order_id=' . $value;
				break;
			case 'user':
				if ($ownerOnly) {
					$q = 'SELECT `virtuemart_vendor_id`
						FROM `#__virtuemart_vmusers` as `au`
						LEFT JOIN `#__virtuemart_userinfos` as `u`
						ON (au.virtuemart_user_id = u.virtuemart_user_id)
						WHERE `u`.`virtuemart_user_id`=' . $value;
				} else {
					$q = 'SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id`= "' . $value . '" ';
				}
				break;
			case 'product':
				$q = 'SELECT virtuemart_vendor_id FROM #__virtuemart_products WHERE virtuemart_product_id=' . $value;
				break;
		}

		$db->setQuery ($q);
		$c[$h] = $db->loadResult ();
		if ($c[$h]) {
			return $c[$h];
		} else {
			$c[$h] = 0;
			return 0;
		}
	}

	/**
	 * This function gives back the storename for the given vendor.
	 *
	 * @author Max Milbers
	 */
	public function getVendorName ($virtuemart_vendor_id = 1) {
		$db = JFactory::getDBO();
		$query = 'SELECT `vendor_store_name` FROM `#__virtuemart_vendors_' . VmConfig::$vmlang . '` WHERE `virtuemart_vendor_id` = "' . (int)$virtuemart_vendor_id . '" ';
		$db->setQuery ($query);
		if ($db->execute ()) {
			return $db->loadResult ();
		} else {
			return '';
		}
	}

	/**
	 * This function gives back the email for the given vendor.
	 *
	 * @author Max Milbers
	 */

	public function getVendorEmail ($virtuemart_vendor_id) {

		$virtuemart_user_id = self::getUserIdByVendorId ((int)$virtuemart_vendor_id);
		if (!empty($virtuemart_user_id)) {
			$query = 'SELECT `email` FROM `#__users` WHERE `id` = "' . $virtuemart_user_id . '" ';
			$db = JFactory::getDBO();
			$db->setQuery ($query);
			if ($db->execute ()) {
				return $db->loadResult ();
			} else {
				return '';
			}
		}
		return '';
	}

	public function getVendorAdressBT ($virtuemart_vendor_id) {

		$userId = self::getUserIdByVendorId ($virtuemart_vendor_id);
		$usermodel = VmModel::getModel ('user');
// 		$usermodel->setId($userId);
		$virtuemart_userinfo_id = $usermodel->getBTuserinfo_id ($userId);
		$vendorAddressBt = $this->getTable ('userinfos');
		$vendorAddressBt->load ($virtuemart_userinfo_id);
		return $vendorAddressBt;
	}

	private $_vendorFields = FALSE;
	public function getVendorAddressFields($vendorId=0){

		if(!$this->_vendorFields){
			if(empty($vendorId)) $vendorId = vmAccess::isSuperVendor();
			$this->_id = (int)$vendorId;
			$userId = VirtueMartModelVendor::getUserIdByVendorId ($vendorId);
			$userModel = VmModel::getModel ('user');
			$virtuemart_userinfo_id = $userModel->getBTuserinfo_id ($userId);

			//TODO should be unecessary, lets change the code: this is needed to set the correct user id for the vendor when the user is logged
			//$userModel->setId($userId);
			$userModel->getVendor($vendorId,FALSE);
			$vendorFieldsArray = $userModel->getUserInfoInUserFields ('mail', 'BT', $virtuemart_userinfo_id, FALSE, TRUE);
			$this->_vendorFields = $vendorFieldsArray[$virtuemart_userinfo_id];
		}

		return $this->_vendorFields;
	}

}
