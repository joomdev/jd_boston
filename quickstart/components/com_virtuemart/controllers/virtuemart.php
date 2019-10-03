<?php
/**
*
* Base controller Frontend
*
* @package		VirtueMart
* @subpackage
* @author Max Milbers
* @link https://virtuemart.net
* @copyright Copyright (c) 2011-2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: virtuemart.php 9871 2018-06-13 16:21:34Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * VirtueMart Component Controller
 *
 * @package		VirtueMart
 */
class VirtueMartControllerVirtuemart extends JControllerLegacy
{

	function __construct() {
		parent::__construct();

	}

	/**
	 * Override of display to prevent caching
	 *
	 * @return  JController  A JController object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = vRequest::getCmd('view', 'virtuemart');
		$view = $this->getView($viewName, $viewType);

		$view->assignRef('document', $document);

		$view->display();

		return $this;
	}

	public function feed(){

		$this->virtuemartFeed = vmRSS::getVirtueMartRssFeed();
		$this->extensionsFeed = vmRSS::getExtensionsRssFeed();

		$document = JFactory::getDocument();
		$headData = $document->getHeadData();
		$headData['scripts'] = array();
		$document->setHeadData($headData);

		ob_clean();
		ob_start();
		include(VMPATH_SITE .'/views/virtuemart/tmpl/feed.php');
		echo ob_get_clean();
		jExit();
	}

	public function keepalive(){
		jExit();
	}
}
 //pure php no closing tag
