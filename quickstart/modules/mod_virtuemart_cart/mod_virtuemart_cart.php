<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/*
*Cart Ajax Module
*
* @version $Id: mod_virtuemart_cart.php 9881 2018-06-20 09:03:58Z Milbo $
* @package VirtueMart
* @subpackage modules
*
* @link https://virtuemart.net
*/

if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
VmConfig::loadConfig();
vmLanguage::loadJLang('mod_virtuemart_cart', true);
vmLanguage::loadJLang('com_virtuemart', true);
vmJsApi::jQuery();

vmJsApi::addJScript("/modules/mod_virtuemart_cart/assets/js/update_cart.js",false,false);

//This is strange we have the whole thing again in controllers/cart.php public function viewJS()
$cart = VirtueMartCart::getCart(false);
$viewName = vRequest::getString('view',0);
if($viewName=='cart'){
	$checkAutomaticPS = true;
} else {
	$checkAutomaticPS = false;
}
$data = $cart->prepareAjaxData();
$currencyDisplay = CurrencyDisplay::getInstance( );
vmJsApi::cssSite();
$moduleclass_sfx 	= $params->get('moduleclass_sfx', '');
$show_price 		= (bool)$params->get( 'show_price', 1 ); // Display the Product Price?
$show_product_list 	= (bool)$params->get( 'show_product_list', 1 ); // Display the Product Price?
require JModuleHelper::getLayoutPath('mod_virtuemart_cart', $params->get('layout', 'default'));
echo vmJsApi::writeJS();
 ?>