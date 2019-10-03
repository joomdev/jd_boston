<?php
/**
*
* Shipment  controller
*
* @package	VirtueMart
* @subpackage Shipment
* @author RickG
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: shipmentmethod.php 10036 2019-04-01 10:28:12Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Shipment  Controller
 *
 * @package    VirtueMart
 * @subpackage Shipment
 * @author RickG, Max Milbers
 */
class VirtuemartControllerShipmentmethod extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		vmLanguage::loadJLang('com_virtuemart_orders',TRUE);
		parent::__construct();
	}

	/**
	 * We want to allow html in the descriptions.
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getPost();

		$data['shipment_name'] = vRequest::getHtml('shipment_name','');
		$data['shipment_desc'] = vRequest::getHtml('shipment_desc','');
		if(isset($data['params'])){
			$data['params'] = vRequest::getHtml('params','');
		}

		parent::save($data);

	}
	/**
	 * Clone a shipment
	 *
	 * @author Valérie Isaksen
	 */
	public function CloneShipment() {

		$app = JFactory::getApplication();

		$model = VmModel::getModel('shipmentmethod');
		$msgtype = '';

		$cids = vRequest::getVar($this->_cidName, vRequest::getInt('virtuemart_shipment_id'));

		foreach($cids as $cid){
			if ($model->createClone($cid)) $msg = vmText::_('COM_VIRTUEMART_SHIPMENT_CLONED_SUCCESSFULLY');
			else {
				$msg = vmText::_('COM_VIRTUEMART_SHIPMENT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}

		$app->redirect('index.php?option=com_virtuemart&view=shipmentmethod', $msg, $msgtype);
	}
}
// pure php no closing tag
