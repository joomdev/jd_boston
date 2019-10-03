<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author
* @link https://virtuemart.net
* @copyright Copyright (c) 2019 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 9924 2018-09-09 07:51:12Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewSupport extends VmViewAdmin {

	function display($tpl = null) {

		self::showACLPref($this);

		parent::display($tpl);
	}


}

//pure php no tag