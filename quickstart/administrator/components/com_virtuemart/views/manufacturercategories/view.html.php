<?php
/**
*
* Manufacturer Category View
*
* @package	VirtueMart
* @subpackage Manufacturer Category
* @author Patrick Kohl
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 9831 2018-05-07 13:45:33Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for maintaining the list of manufacturer categories
 *
 * @package	VirtueMart
 * @subpackage Manufacturer Categories
 * @author Patrick Kohl
 */
class VirtuemartViewManufacturercategories extends VmViewAdmin {

	function display($tpl = null) {

		// get necessary model
		$model = VmModel::getModel();

		$this->SetViewTitle('MANUFACTURER_CATEGORY');

     	$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$manufacturerCategory = $model->getData();
			$this->assignRef('manufacturerCategory',	$manufacturerCategory);

			$this->addStandardEditViewCommands($manufacturerCategory->virtuemart_manufacturercategories_id);

        }
        else {
        	$this->addStandardDefaultViewCommands();
        	$this->addStandardDefaultViewLists($model);

			$manufacturerCategories = $model->getManufacturerCategories();
			$this->assignRef('manufacturerCategories',	$manufacturerCategories);

			$this->pagination = $model->getPagination();

		}
		parent::display($tpl);
	}

}
// pure php no closing tag
