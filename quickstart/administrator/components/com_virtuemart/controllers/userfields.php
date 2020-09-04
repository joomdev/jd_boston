<?php
/**
*
* Userfields controller
*
* @package	VirtueMart
* @subpackage Userfields
* @author Oscar van Eijk
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: userfields.php 10250 2020-02-13 08:37:20Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Controller class for the Order status
 *
 * @package    VirtueMart
 * @subpackage Userfields
 * @author     Oscar van Eijk
 */
class VirtuemartControllerUserfields extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access public
	 * @author
	 */
	function __construct(){
		parent::__construct('virtuemart_userfield_id');

	}

	function save($data = 0) {

		if($data===0) $data = vRequest::getPost();

		$data['description'] = vRequest::getHtml('description','');
		if(isset($data['params'])){
			$data['params'] = vRequest::getHtml('params','');
		}

		$data['name'] = vRequest::getCmd('name');
		// onSaveCustom plugin;
		parent::save($data);
	}



}

//No Closing tag
