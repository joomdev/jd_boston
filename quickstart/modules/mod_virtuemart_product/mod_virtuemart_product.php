<?php
defined('_JEXEC') or die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/*
* featured/Latest/Topten/Random Products Module
*
* @version $Id: mod_virtuemart_product.php 2789 2011-02-28 12:41:01Z oscar $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2010 - Patrick Kohl
* @copyright (C) 2011 - 2016 The VirtueMart Team
* @author Max Milbers, Valerie Isaksen, Alexander Steiner
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* @link https://virtuemart.net
*/


defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

VmConfig::loadConfig();
vmLanguage::loadJLang('mod_virtuemart_product', true);

// Setting
$max_items = 		$params->get( 'max_items', 2 ); //maximum number of items to display
$layout = $params->get('layout','default');
$category_id = 		$params->get( 'virtuemart_category_id', null ); // Display products from this category only
$filter_category = 	(bool)$params->get( 'filter_category', 0 ); // Filter the category
$display_style = 	$params->get( 'display_style', "div" ); // Display Style
$products_per_row = $params->get( 'products_per_row', 1 ); // Display X products per Row
$show_price = 		(bool)$params->get( 'show_price', 1 ); // Display the Product Price?
$show_addtocart = 	(bool)$params->get( 'show_addtocart', 1 ); // Display the "Add-to-Cart" Link?
$headerText = 		$params->get( 'headerText', '' ); // Display a Header Text
$footerText = 		$params->get( 'footerText', ''); // Display a footerText
$Product_group = 	$params->get( 'product_group', 'featured'); // Display a footerText

$mainframe = Jfactory::getApplication();
$virtuemart_currency_id = $mainframe->getUserStateFromRequest( "virtuemart_currency_id", 'virtuemart_currency_id',vRequest::getInt('virtuemart_currency_id',0) );


vmJsApi::jPrice();
vmJsApi::cssSite();

$cache = $params->get( 'vmcache', true );
$cachetime = $params->get( 'vmcachetime', 300 );
//vmdebug('$params for mod products',$params);
if($cache){
	vmdebug('Use cache for mod products');
	$key = 'products'.$category_id.'.'.$max_items.'.'.$filter_category.'.'.$display_style.'.'.$products_per_row.'.'.$show_price.'.'.$show_addtocart.'.'.$Product_group.'.'.$virtuemart_currency_id.'.'.$category_id;
	$cache	= VmConfig::getCache('mod_virtuemart_product', 'output');
	$cache->setCaching(1);
	$cache->setLifeTime($cachetime);

	if ($output = $cache->get($key)) {
		echo $output;
		echo vmJsApi::writeJS();
		vmdebug('Use cached mod products');
		return true;
	}
}

$vendorId = vRequest::getInt('vendorid', 1);

if ($filter_category ) $filter_category = TRUE;

$productModel = VmModel::getModel('Product');
VirtueMartModelProduct::$omitLoaded = $params->get( 'omitLoaded', 0);
$products = $productModel->getProductListing($Product_group, $max_items, $show_price, true, false,$filter_category, $category_id);
$productModel->addImages($products);

if (!class_exists('shopFunctionsF'))
	require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
shopFunctionsF::sortLoadProductCustomsStockInd($products,$productModel);

$totalProd = 		count( $products);
if(empty($products)) return false;

if (!class_exists('CurrencyDisplay'))
	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
$currency = CurrencyDisplay::getInstance( );

ob_start();

/* Load tmpl default */
require(JModuleHelper::getLayoutPath('mod_virtuemart_product',$layout));
$output = ob_get_clean();
echo $output;

if($cache){
	$cache->store($output, $key);
}

echo vmJsApi::writeJS();
?>
