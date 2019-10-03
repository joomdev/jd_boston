<?php

/**
 *
 * @package	VirtueMart
 * @subpackage   Models Fields
 * @author Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id:$
 */
defined('JPATH_BASE') or die;

if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

/**
 * Supports a modal category picker.
 *
 *
 */
class JFormFieldVmCategory extends JFormField
{
	var $type = 'category';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */

     protected function getInput() {

		VmConfig::loadConfig();
		vmLanguage::loadJLang('com_virtuemart');
        vmLanguage::loadJLang('com_virtuemart_config');
	    $categorylist = ShopFunctions::categoryListTree(array($this->value));

        $html = '<select class="inputbox"   name="' . $this->name . '" >';
        $html .= '<option value="0">' . vmText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL') . '</option>';
        $html .= $categorylist;
        $html .="</select>";
        return $html;

    }

}