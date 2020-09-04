<?php
/**
 * Shows prices of shipment and payment plugins
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2018 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */

$plugin = $viewData['plugin'];
$psType = $viewData['psType'];
$selectedPlugin = $viewData['selectedPlugin'];
$pluginSalesPrice = $viewData['pluginSalesPrice'];
$currency = $viewData['currency'];

$pluginName = $psType . '_name';
$pluginmethod_id = 'virtuemart_'.$psType.'method_id';

if ($selectedPlugin == $plugin->{$pluginmethod_id}) {
	$checked = 'checked="checked"';
} else {
	$checked = '';
}

$costDisplay = "";
if ($pluginSalesPrice) {
	$costDisplay = $currency->priceDisplay( $pluginSalesPrice );
	$t = vmText::_( 'COM_VIRTUEMART_PLUGIN_COST_DISPLAY' );
	if(strpos($t,'/')!==FALSE){
		list($discount, $fee) = explode( '/', vmText::_( 'COM_VIRTUEMART_PLUGIN_COST_DISPLAY' ) );
		if($pluginSalesPrice>=0) {
			$costDisplay = '<span class="'.$plugin->folder.'_cost fee"> ('.$fee.' '.$costDisplay.")</span>";
		} else if($pluginSalesPrice<0) {
			$costDisplay = trim(strip_tags($costDisplay),'-');
			$costDisplay = '<span class="'.$plugin->folder.'_cost discount"> ('.$discount.' '.$costDisplay.")</span>";
		}
	} else {
		$costDisplay = '<span class="'.$plugin->folder.'_cost fee"> ('.$t.' '.$costDisplay.")</span>";
	}
}
$dynUpdate='';
if( VmConfig::get('oncheckout_ajax',false)) {
	$dynUpdate=' data-dynamic-update="1" ';
}
echo '<input type="radio"'.$dynUpdate.' name="' . $pluginmethod_id . '" id="' . $psType . '_id_' . $plugin->{$pluginmethod_id} . '"   value="' . $plugin->{$pluginmethod_id} . '" ' . $checked . ">\n"
. '<label for="' . $psType . '_id_' . $plugin->{$pluginmethod_id} . '">' . '<span class="' . $plugin->folder . '">' . $plugin->{$pluginName} . $costDisplay . "</span></label>\n";