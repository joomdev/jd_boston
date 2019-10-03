<?php
/**
*
* Category controller
*
* @package	VirtueMart
* @subpackage Category
* @author Max Milbers, jseros
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2018 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 9999 2018-12-13 11:05:18Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


class VirtuemartControllerCategory extends VmController {


	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		$data = vRequest::getRequest();

		$this->getStrByAcl(array('category_description','category_name'),$data);

		parent::save($data);
	}


	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderUp()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}

		// Check token
		vRequest::vmCheckToken();

		//capturing virtuemart_category_id
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_virtuemart&view=category', vmText::_('COM_VIRTUEMART_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = VmModel::getModel('category');

		$msg = '';
		if ($model->orderCategory($id, -1)) {
			$msg = vmText::_('COM_VIRTUEMART_ITEM_MOVED_UP');
		}

		$this->setRedirect( 'index.php?option=com_virtuemart&view=category', $msg );
	}


	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderDown()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		// Check token
		vRequest::vmCheckToken();

		//capturing virtuemart_category_id
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_virtuemart&view=category', vmText::_('COM_VIRTUEMART_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = VmModel::getModel('category');

		$msg = '';
		if ($model->orderCategory($id, 1)) {
			$msg = vmText::_('COM_VIRTUEMART_ITEM_MOVED_DOWN');
		}

		$this->setRedirect( 'index.php?option=com_virtuemart&view=category', $msg );
	}


	/**
	* Save the categories order
	*/
	public function saveOrder()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		// Check for request forgeries
		vRequest::vmCheckToken();

		$cid	= vRequest::getInt( 'cid', array() );	//is sanitized

		$model = VmModel::getModel('category');

		$order	= vRequest::getInt('order', array() );

		$msg = '';
		if ($model->setOrder($cid,$order)) {
			$msg = vmText::_('COM_VIRTUEMART_NEW_ORDERING_SAVED');
		}

		$this->setRedirect('index.php?option=com_virtuemart&view=category', $msg );
	}

}
