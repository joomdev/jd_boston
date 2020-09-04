<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage Paymentmethod
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_edit.php 3420 2011-06-04 12:37:20Z Electrocity $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<div class="col50">
    <fieldset>
        <legend><?php echo vmText::_('COM_VIRTUEMART_SHIPMENTMETHOD'); ?></legend>
        <table class="admintable">
	    <?php echo VmHTML::row('input', 'COM_VIRTUEMART_SHIPPING_FORM_NAME', 'shipment_name', $this->shipment->shipment_name,'class="required"'); ?>
		<?php echo VmHTML::row('input', 'COM_VIRTUEMART_SLUG', 'slug', $this->shipment->slug); ?>
	    <?php echo VmHTML::row('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->shipment->published); ?>
	    <?php echo VmHTML::row('textarea', 'COM_VIRTUEMART_SHIPPING_FORM_DESCRIPTION', 'shipment_desc', $this->shipment->shipment_desc); ?>
	    <?php echo VmHTML::row('raw', 'COM_VIRTUEMART_SHIPPING_CLASS_NAME', $this->pluginList);

	    if($this->checkConditionsCore){
//		quorvia colors
			echo VmHTML::row( 'color', 'COM_VIRTUEMART_SHIPMENT_METHOD_COLOR', 'display_color', $this->shipment->display_color, '', 'value', 'text', false );
            echo VmHTML::row('input', 'COM_VM_METHD_MIN_AMOUNT', 'min_amount', $this->shipment->min_amount);
			echo VmHTML::row('input', 'COM_VM_METHD_MAX_AMOUNT', 'max_amount', $this->shipment->max_amount);
	    }

	    echo VmHTML::row('raw', 'COM_VIRTUEMART_SHIPPING_FORM_SHOPPER_GROUP', $this->shopperGroupList);


		if($this->checkConditionsCore){

			$raw = '<select class="inputbox multiple" id="categories" name="categories[]" multiple="multiple" size="10">
					'.  ShopFunctions::categoryListTree($this->shipment->categories) .'
            </select>';
			echo VmHTML::row('raw', 'COM_VIRTUEMART_CATEGORIES',$raw);

			$raw = '<select class="inputbox multiple" id="blocking_categories" name="blocking_categories[]" multiple="multiple" size="10">
					'.  ShopFunctions::categoryListTree($this->shipment->blocking_categories) .'
            </select>';

			echo VmHTML::row('raw', 'COM_VIRTUEMART_CATEGORIES_BLOCKING',$raw);

			echo VmHTML::row('raw', 'COM_VIRTUEMART_COUNTRIES',ShopFunctionsF::renderCountryList($this->shipment->countries,True, array(), '', 0, 'countries', 'countries'));
			echo VmHTML::row('raw', 'COM_VIRTUEMART_COUNTRIES_BLOCKING',ShopFunctionsF::renderCountryList($this->shipment->blocking_countries,True, array(), '', 0, 'blocking_countries', 'blocking_countries'));
		}


/*            $raw = '<select class="vm-drop" id="blocking_categories" name="blocking_categories[]" multiple="multiple"  data-placeholder="'.vmText::_('COM_VIRTUEMART_DRDOWN_SELECT_SOME_OPTIONS').'" >
                    <option value="-2" selected="selected">Do not store</option>
                </select>';*/
		//$raw = ShopFunctions::categoryListTree($this->shipment->blocking_categories);

	    echo VmHTML::row('input', 'COM_VIRTUEMART_LIST_ORDER', 'ordering', $this->shipment->ordering, 'class="inputbox"', '', 4, 4); ?>
		<?php echo VmHTML::row('raw', 'COM_VIRTUEMART_CURRENCY', $this->currencyList); ?>
	    <?php
	    if ($this->showVendors()) {
			echo VmHTML::row('raw', 'COM_VIRTUEMART_VENDOR', $this->vendorList);
	    }
		if($this->showVendors ){
			echo VmHTML::row('checkbox','COM_VIRTUEMART_SHARED', 'shared', $this->shipment->shared );
		}
	    ?>
        </table>
    </fieldset>
</div>


