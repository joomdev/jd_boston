<?php
/**
*
* Data module for shop countries
*
* @package	VirtueMart
* @subpackage Country
* @author RickG, Max Milbers, jseros
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: state.php 10116 2019-09-03 10:25:22Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Model class for shop countries
 *
 * @package	VirtueMart
 * @subpackage State
 * @author RickG, Max Milbers
 */
class VirtueMartModelState extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('virtuemart_state_id');
		$this->setMainTable('states');
		$this->_selectedOrderingDir = 'ASC';
	}

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * Renamed to getSingleState to avoid overwriting by jseros
     *
     * @author Max Milbers
     */
	function getSingleState($id = 0){

		return $this->getData($id);
	}


	/**
	 * Retireve a list of countries from the database.
	 *
     * @author RickG, Max Milbers
	 * @return object List of state objects
	 */
	public function getStates($countryId, $noLimit=false, $published = false)
	{
		static $c = array();
		$h = $countryId.'.'.(int)$noLimit.(int)$published;
		if(isset($c[$h])){
			$this->_data = $c[$h];
			return $c[$h];
		} else {
			$quer= 'SELECT * FROM `#__virtuemart_states`  WHERE `virtuemart_country_id`= "'.(int)$countryId.'" ';
			if($published){
				$quer .= 'AND `published`="1" ';
			}

			$quer .= 'ORDER BY `#__virtuemart_states`.`state_name`';

			if ($noLimit) {
				$c[$h] = $this->_getList($quer);
			}
			else {
				$c[$h] = $this->_getList($quer, $this->getState('limitstart'), $this->getState('limit'));
			}

			if(count($c[$h]) >0){
				$this->_total = $this->_getListCount($quer);
			}
			$this->_data = $c[$h];
			return $c[$h];
		}

	}

	/**
	 * Tests if a state and country fits together and if they are published
	 *
	 * @author Max Milbers
	 * @return String Attention, this function gives a 0=false back in case of success
	 */
	public static function testStateCountry(&$countryId, &$stateId, &$required) {

		static $c = array();
		$countryId = (int)$countryId;
		$stateId = (int)$stateId;

		if(empty($countryId)) return true;

		$cTable = VmTable::getInstance('countries', 'Table');

		$country = $cTable->load($countryId, 0, 'AND `published`="1"');

		if($country->published){

			//Test if country has states
			if(!isset($c[$countryId])){
				$db = JFactory::getDBO();
				$q = 'SELECT COUNT(*) FROM `#__virtuemart_states`  WHERE `virtuemart_country_id`= "'.$countryId.'" AND `published`="1"';
				$db->setQuery($q);
				$c[$countryId] = $db->loadResult();
			}

			if($c[$countryId]){

				if(!empty($stateId)){
					$h = $countryId.'.'.$stateId;
					if(!isset($c[$h])){
						$db = JFactory::getDBO();
						//Test if virtuemart_state_id fits to virtuemart_country_id
						$q = 'SELECT * FROM `#__virtuemart_states` WHERE `virtuemart_country_id`= "'.$countryId.'" AND `virtuemart_state_id`="'.$stateId.'" and `published`="1"';
						$db->setQuery($q);
						$c[$h] = $db->loadResult();
					}

					if($c[$h]){
						return true;
					} else {
						//There is a country, but the state does not exist or is unlisted
						$stateId = 0;
						vmLanguage::loadJLang('com_virtuemart_countries');
						vmInfo('COM_VIRTUEMART_COUNTRY_STATE_NOTEXIST');
						return false;
					}
				}

			} else {
				//This country has no states listed
				$stateId = 0;
				$required = false;
				return true;
			}

		} else {
			//The given country does not exist, this can happen, when non published country was chosen
			$countryId = 0;
			$stateId = 0;
			vmLanguage::loadJLang('com_virtuemart_countries');
			vmInfo('COM_VIRTUEMART_COUNTRY_NOTEXIST');
			return false;
		}
	}

	function store(&$data){
		if(!vmAccess::manager('country')){
			vmWarn('Insufficient permissions to store state');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('country')){
			vmWarn('Insufficient permissions to remove state');
			return false;
		}
		return parent::remove($ids);
	}
}
// pure php no closing tag