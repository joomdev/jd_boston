<?php
/**
*
* Users table
*
* @package	VirtueMart
* @subpackage User
* @author Max Milbers
* @link https://virtuemart.net
* @copyright Copyright (c) 2010 - 2014 VirtueMart Team and authors. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: user_shoppergroup.php 2420 2010-06-01 21:12:57Z oscar $
*/

defined('_JEXEC') or die('Restricted access');

class TableVmusers extends VmTableData {

	/** @var int Vendor ID */
	var $virtuemart_user_id		= 0;
	var $user_is_vendor 		= 0;
	var $virtuemart_vendor_id 	= 0;
	var $customer_number 		= 0;
	var $virtuemart_paymentmethod_id = 0;
	var $virtuemart_shipmentmethod_id = 0;
	var $agreed					= 0;

	function __construct(&$db) {
		parent::__construct('#__virtuemart_vmusers', 'virtuemart_user_id', $db);

		$this->setPrimaryKey('virtuemart_user_id');

		$this->setLoggable();

		$this->setTableShortCut('vmu');
	}

	function check() {

		$multix = VmConfig::get('multix', 'none');
		$loggedVendorId = vmAccess::isSuperVendor(0,'user.editshop');

		$tbl_key = $this->_tbl_key;

		$q = 'SELECT `virtuemart_vendor_id`,`user_is_vendor`,`virtuemart_user_id` FROM `' . $this->_tbl . '` WHERE `' . $this->_tbl_key . '`="' . $this->{$tbl_key} . '" ';
		$h = $this->_tbl.$this->_tbl_key.$this->{$tbl_key};
		if (!isset(self::$_cache[$h])) {
			$this->_db->setQuery($q);
			$vmuser = $this->_db->loadAssoc();
			self::$_cache[$h] = $vmuser;
		} else $vmuser = self::$_cache[$h];

		//vmdebug('Table '.$this->_tbl.' check loaded old entry',$loggedVendorId,$vmuser);

		if($vmuser){
			if(!vmAccess::manager('user.edit')){
				$user = JFactory::getUser();
				if(!empty($vmuser->virtuemart_user_id) and $user->id!=$vmuser->virtuemart_user_id){
					$msg = 'Blocked storing, logged user ' . $user->id . ' tried to store '.$this->virtuemart_user_id.' but data belongs to ' . $vmuser->virtuemart_user_id;
					vmdebug($msg,$this->_tbl);
					vmError($msg,$msg);
					return false;
				}
			}

			if(!vmAccess::manager('managevendors')){
				if(!empty($vmuser->virtuemart_vendor_id) and !empty($this->virtuemart_vendor_id) and $vmuser->virtuemart_vendor_id!=$this->virtuemart_vendor_id){
					$msg = 'Blocked storing, logged vendor ' . $loggedVendorId . ' tried to store '.$this->virtuemart_vendor_id.' but data belongs to ' . $vmuser->virtuemart_vendor_id;
					vmdebug($msg,$this->_tbl);
					vmError($msg,$msg);
					return false;
				} else if(!empty($vmuser->virtuemart_vendor_id)){
					vmdebug('!empty($vmuser->virtuemart_vendor_id) '.$vmuser->virtuemart_vendor_id.' overwriting $this->virtuemart_vendor_id '.$this->virtuemart_vendor_id);
					$this->virtuemart_vendor_id = $vmuser->virtuemart_vendor_id;
				} else if(!empty($this->virtuemart_vendor_id)){
					vmdebug('empty($vmuser->virtuemart_vendor_id) set $this->virtuemart_vendor_id '.$this->virtuemart_vendor_id.' to '.$loggedVendorId);
					$this->virtuemart_vendor_id = $loggedVendorId;
				}
			} else if(empty($this->virtuemart_vendor_id) and !empty($vmuser->virtuemart_vendor_id)){
				vmdebug('managevendors lost virtuemart_vendor_id set to '.$vmuser->virtuemart_vendor_id);
				$this->virtuemart_vendor_id = $vmuser->virtuemart_vendor_id;
			}
		}	//Fallback for single vendor stores.
		else if($multix=='none' and empty($this->user_is_vendor)){
			$this->virtuemart_vendor_id = 0;
		}

		//if($multix!='none'){
		if($this->virtuemart_vendor_id==1){
			$this->user_is_vendor = 1;
		}

		if($this->virtuemart_vendor_id == 1 and !vmAccess::manager('user.editshop')){
			$msg = 'You do not have the permission to change the shop data';
			vmWarn($msg,$msg);
			return false;
		}

		if(!empty($this->virtuemart_vendor_id)){
			$q = 'SELECT `virtuemart_vendor_id`,`user_is_vendor`,`virtuemart_user_id` FROM `' . $this->_tbl . '` WHERE `virtuemart_vendor_id`="' . $this->virtuemart_vendor_id . '" ';
			$h = ($this->_tbl.'virtuemart_vendor_id'.$this->virtuemart_vendor_id);
			if (!isset(self::$_cache[$h])) {
				$this->_db->setQuery( $q );
				$vmVends = $this->_db->loadAssocList();
				$c = count( $vmVends);
				if($vmVends and $c>0 ) {
					self::$_cache[$h] = $vmVends[0];vmdebug('my $vmVends',$q,$vmVends,$c);
					if($c>1) {
						vmError( 'There is a serious problem with your store, there are entries with the same virtuemart_vendor_id '.$this->virtuemart_vendor_id.' enable the vmdebug or check your virtuemart log files and fix it immediatly. Use the setStoreOwner function in Tools and Migration', 'There is a problem with the store, please contact the shop owner' );
						$t = VmConfig::$logDebug;

						foreach($vmVends as $vend){
							vmdebug('Entries with the same vendor id',$vend);
							VmConfig::$logDebug = 1;
							vmdebug('Entries with the same vendor id',$vend);
							VmConfig::$logDebug = 0;
						}
						VmConfig::$logDebug = $t;
						return false;
					} else if($c==1) {
						vmdebug('Found one entry with the same vendor id',$c[0]['virtuemart_user_id'],$this->virtuemart_user_id);
						if(!empty($vmVends[0]['virtuemart_user_id']) and $vmVends[0]['virtuemart_user_id']!=$this->virtuemart_user_id){
							vmError('Storing of user with '.$this->virtuemart_user_id.' cancelled, there exists already a user '.$vmVends[0]['virtuemart_user_id'].' with the virtuemart_vendor_id '.$this->virtuemart_vendor_id, 'You do not have the permission to change the data of this user' );
							return false;
						}
					}
				} else {
					self::$_cache[$h] = false;
				}
			}
		}

		return true;
	}
}
