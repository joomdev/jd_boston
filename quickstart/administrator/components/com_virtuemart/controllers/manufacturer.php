<?php
/**
*
* Manufacturer controller
*
* @package	VirtueMart
* @subpackage Manufacturer
* @author Patrick Kohl, Franz-Peter Scherer
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2018 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturer.php 9999 2018-12-13 11:05:18Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


/**
 * Manufacturer Controller
 *
 * @package    VirtueMart
 * @subpackage Manufacturer
 * @author
 *
 */
class VirtuemartControllerManufacturer extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('virtuemart_manufacturer_id');

	}

	/**
	 * Handle the save task
	 * Checks already in the controller the rights and sets the data by filtering the post
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		/* Load the data */
		$data = vRequest::getRequest();
		/* add the mf desc as html code */
		$this->getStrByAcl(array('mf_desc'),$data);

		parent::save($data);
	}
}
// pure php no closing tag
