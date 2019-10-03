<?php
/**
 * sublayout order by lists
 *
 * @package    VirtueMart
 * @author     Max Milbers
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version    $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

// Joomla Security Check - no direct access to this file
// Prevents File Path Exposure
defined('_JEXEC') or die('Restricted access');

// get virtuemart product model
$productModel = VmModel::getModel('product');

// get the virtuemart category id
$virtuemart_category_id = vRequest::getInt('virtuemart_category_id', -1);

$getArray = vRequest::getGet(FILTER_SANITIZE_STRING);
if (!isset($getArray['view'])) {
	$getArray['view'] = 'category';
}
if (!isset($getArray['virtuemart_category_id'])) {
	$getArray['virtuemart_category_id'] = 0;
}

$Itemid = '';

$fieldLink = vmURI::getCurrentUrlBy('request', FALSE, TRUE, array ('orderby', 'dir'));

$orderDirLink = '';
$orderDirConf = VmConfig::get('prd_brws_orderby_dir');
$orderDir     = vRequest::getCmd('dir', $orderDirConf);
if ($orderDir != $orderDirConf) {
	$orderDirLink .= '&dir=' . $orderDir;    //was '&order='
}

$orderbyTxt = '';
$orderbyCfg = VmConfig::get('browse_orderby_field');
$orderby    = vRequest::getString('orderby', $orderbyCfg);
$orderby    = $productModel->checkFilterOrder($orderby);

if ($orderby != $orderbyCfg) {
	$orderbyTxt = '&orderby=' . $orderby;
}

$manufacturerTxt  = '';
$manufacturerLink = '';
if (VmConfig::get('show_manufacturers')) {

	$manuM = VmModel::getModel('manufacturer');
	vmSetStartTime('mcaching');
	$mlang = vmLanguage::getUseLangFallback();

	if (TRUE) {
		$cache = VmConfig::getCache('com_virtuemart_cat_manus', 'callback');
		$cache->setCaching(TRUE);
		$manufacturers = $cache->call(
			array ('VirtueMartModelManufacturer', 'getManufacturersOfProductsInCategory'),
			$virtuemart_category_id,
			VmConfig::$vmlang,
			$mlang
		);
		vmTime('Manufacturers by Cache', 'mcaching');
	} else {
		$manufacturers = $manuM->getManufacturersOfProductsInCategory(
			$virtuemart_category_id,
			VmConfig::$vmlang,
			$mlang
		);
		vmTime('Manufacturers by function', 'mcaching');
	}

	// manufacturer link list
	$manufacturerLink           = '';
	$virtuemart_manufacturer_id = vRequest::getInt('virtuemart_manufacturer_id', 0);
	if (!empty($virtuemart_manufacturer_id)) {
		$manufacturerTxt = '&virtuemart_manufacturer_id=' . $virtuemart_manufacturer_id;
	}

	if (count($manufacturers) > 0) {
		if (count($manufacturers) > 1) {
			$manufacturerLink = '';
			if ($virtuemart_manufacturer_id > 0) {
				$allLink   = str_replace($manufacturerTxt, $fieldLink, '');
				$allLink   .= '&virtuemart_manufacturer_id=0';
				$valueLink = JRoute::_($allLink . $orderbyTxt . $orderDirLink, FALSE);

				$manufacturerLink .= '<option value="' . $valueLink . '">' . vmText::_(
						'COM_VIRTUEMART_SEARCH_SELECT_ALL_MANUFACTURER'
					) . '</option>';
			}

			foreach ($manufacturers as $mf) {
				$l    = str_replace(
						$manufacturerTxt,
						'',
						$fieldLink
					) . '&virtuemart_manufacturer_id=' . $mf->virtuemart_manufacturer_id . $orderbyTxt . $orderDirLink . $Itemid;
				$link = JRoute::_($l, FALSE);

				if ($mf->virtuemart_manufacturer_id != $virtuemart_manufacturer_id) {
					$manufacturerLink .= '<option value="' . $link . '">' . $mf->mf_name . '</option>';
				} else {
					$manufacturerLink        .= '<option selected value="-">' . $mf->mf_name . '</option>';
					$currentManufacturerLink = '<option selected value="-">' . $mf->mf_name . '</option>';
				}
			}
		} else if ($virtuemart_manufacturer_id > 0) {
			$currentManufacturerLink = '<option value="-">' . $manufacturers[0]->mf_name . '</option>';
		} else {
			$currentManufacturerLink = '<option value="-">' . $manufacturers[0]->mf_name . '</option>';
		}
	}
}

/* order by link list*/
$orderByLink = '';
$fields      = VmConfig::get('browse_orderby_fields');
if (count($fields) > 1) {
	$orderByLink = '';
	foreach ($fields as $field) {
		$dotps = strrpos($field, '.');
		if ($dotps !== FALSE) {
			$prefix             = substr($field, 0, $dotps + 1);
			$fieldWithoutPrefix = substr($field, $dotps + 1);
		} else {
			$prefix             = '';
			$fieldWithoutPrefix = $field;
		}

		$text  = vmText::_(
			'COM_VIRTUEMART_' . strtoupper(
				str_replace(
					array (',', ' '),
					array ('_', ''),
					$fieldWithoutPrefix
				)
			)
		);
		$field = explode('.', $field);
		if (isset($field[1])) {
			$field = $field[1];
		} else {
			$field = $field[0];
		}

		$link = JRoute::_($fieldLink . $manufacturerTxt . '&orderby=' . $field . $Itemid, FALSE);

		// echeck whether this is the active one
		$orderby = vRequest::getString('orderby', $orderbyCfg);
		$orderby = $productModel->checkFilterOrder($orderby);
		$dotps   = strrpos($orderby, ',');
		if ($dotps !== FALSE) {
			$prefix  = substr($orderby, 0, $dotps + 1);
			$orderby = substr($orderby, $dotps + 1);
		}
		$selected = ($field == $orderby) ? ' selected' : '';

		$orderByLink .= '<option' . $selected . ' value="' . $link . '">' . $text . '</option>';
	}
}


// full string list
if ($orderby == '') {
	$orderby = $orderbyCfg;
}
$orderby = strtoupper($orderby);


$dotps = strrpos($orderby, '.');
if ($dotps !== FALSE) {
	$prefix  = substr($orderby, 0, $dotps + 1);
	$orderby = substr($orderby, $dotps + 1);
} else {
	$prefix = '';
}

// <select class="form-control" id="inputGroupSelect04">
//     <option selected>Choose...</option>
//     <option value="1">One</option>
//     <option value="2">Two</option>
//     <option value="3">Three</option>
//   </select>

$orderByList = '<div class="form-group">';
$orderByList .= '<label for="vm-store-order-by-list">' . vmText::_('COM_VIRTUEMART_ORDERBY') . '</label>';
$orderByList .= '<select class="form-control" id="vm-store-order-by-list" name="vm-store-order-by-list" onchange="window.top.location.href=this.options[this.selectedIndex].value">';
$orderByList .= $orderByLink;
$orderByList .= '</select>';
$orderByList .= '</div>';


$manuList = '';
if (VmConfig::get('show_manufacturers') && count($manufacturers) > 0) {
	$manuList = '<div class="form-group">';
	$manuList .= '<label for="vm-store-order-manufacturer-list">' . vmText::_(
			'COM_VIRTUEMART_SEARCH_SELECT_MANUFACTURER'
		) . '</label>';
	$manuList .= '<select class="form-control" id="vm-store-order-manufacturer-list" name="vm-store-order-manufacturer-list" onchange="window.top.location.href=this.options[this.selectedIndex].value">';

	if (empty($currentManufacturerLink)) {
		$manuList .= '<option value="-">' . vmText::_('COM_VIRTUEMART_SEARCH_SELECT_MANUFACTURER') . '</option>';
	}

	$manuList .= $manufacturerLink;
	$manuList .= '</select>';
	$manuList .= '</div>';
}

$orderbyLists = array ('orderby' => $orderByList, 'manufacturer' => $manuList);


echo self::renderVmSubLayoutAsGrid(
	'vm-grid-item',
	array ('order-by-lists' => $orderbyLists,
		   'options'        => array ('items_per_row'      => array ('xs' => 1,
																	 'sm' => 2,
																	 'md' => 2,
																	 'lg' => 2,
																	 'xl' => 2,),
									  'show_vertical_line' => TRUE,),)
);