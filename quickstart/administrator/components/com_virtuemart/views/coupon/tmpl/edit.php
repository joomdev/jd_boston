<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage Coupon
 * @author RickG
 * @author Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit.php 10290 2020-04-07 10:20:41Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'COM_VIRTUEMART_COUPON_DETAILS');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

	<fieldset>
	    <legend><?php echo vmText::_('COM_VIRTUEMART_COUPON_DETAILS'); ?></legend>
	    <table class="admintable">
			<?php echo VmHTML::row('input','COM_VIRTUEMART_COUPON','coupon_code',$this->coupon->coupon_code,'class="required"','',20,32); ?>
			<?php echo VmHTML::row('checkbox','COM_VIRTUEMART_PUBLISHED','published',$this->coupon->published); ?>
			<?php echo VmHTML::row('input','COM_VIRTUEMART_VALUE','coupon_value',$this->coupon->coupon_value,'class="required"','',10,32); ?>

			<?php
				$radioOptions = array();
				$radioOptions[] = JHtml::_('select.option', 'percent', vmText::_('COM_VIRTUEMART_COUPON_PERCENT'));
				$radioOptions[] = JHtml::_('select.option', 'total', vmText::_('COM_VIRTUEMART_COUPON_TOTAL'));
				echo VmHTML::row('radio','COM_VIRTUEMART_COUPON_PERCENT_TOTAL','percent_or_total',$radioOptions,$this->coupon->percent_or_total); ?>
			<?php
				$listOptions = array();
				$listOptions[] = JHtml::_('select.option', 'permanent', vmText::_('COM_VIRTUEMART_COUPON_TYPE_PERMANENT'));
				$listOptions[] = JHtml::_('select.option', 'gift', vmText::_('COM_VIRTUEMART_COUPON_TYPE_GIFT'));
				 echo VmHTML::row('select','COM_VIRTUEMART_COUPON_TYPE', 'coupon_type', $listOptions ,$this->coupon->coupon_type,'','value', 'text',false) ; ?>
 			<?php echo VmHTML::row('input','COM_VIRTUEMART_COUPON_VALUE_VALID_AT','coupon_value_valid', $this->coupon->coupon_value_valid, 'class="inputbox"','',10,255,' ' . $this->vendor_currency ); ?>
			
			<?php /* Malik Coupon */
			echo VmHTML::row('input','Maximum Discount Threshold','coupon_value_max', $this->coupon->coupon_value_max, 'class="inputbox"','',10,255,' ' . $this->vendor_currency ); 
			?>
 			<?php 
			echo VmHTML::row('input','Maximum Allowable Coupon Usage per User','virtuemart_coupon_max_attempt_per_user', $this->coupon->virtuemart_coupon_max_attempt_per_user, 'class="inputbox"','',10,255,' '); 
			?>
			
			<tr>
				<td class="key">
					<label for="virtuemart_shoppergroup_id">
						<?php echo "Allowed Shoppers"; ?>
					</label>
				</td>
				<td>
					<?php echo $this->lists['vmusers']; ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<label for="virtuemart_shoppergroup_id">
						<?php echo "Exclude Shopper Groups"; ?>
					</label>
				</td>
				<td>
					<?php echo $this->lists['shoppergroups']; ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<label for="virtuemart_shoppergroup_id">
						<?php echo "Allowed Products"; ?>
					</label>
				</td>
				<td>
					<?php echo $this->lists['products']; ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<label for="virtuemart_shoppergroup_id">
						<?php echo "Allowed Product Categories"; ?>
					</label>
				</td>
				<td>
					<?php echo $this->lists['productcategories']; ?>
				</td>
			</tr>
			
			<?php echo VmHTML::row('raw','COM_VIRTUEMART_COUPON_START',  vmJsApi::jDate($this->coupon->coupon_start_date , 'coupon_start_date') ); ?>
			<?php echo VmHTML::row('raw','COM_VIRTUEMART_COUPON_EXPIRY', vmJsApi::jDate($this->coupon->coupon_expiry_date,'coupon_expiry_date') ); ?>
			<?php if($this->showVendors()){
				echo VmHTML::row('raw','COM_VIRTUEMART_VENDOR', $this->vendorList );
			}
			?>
	    </table>
	</fieldset>
    <input type="hidden" name="virtuemart_coupon_id" value="<?php echo $this->coupon->virtuemart_coupon_id; ?>" />

 	<?php echo $this->addStandardHiddenToForm(); ?>
</form>


    <?php
    AdminUIHelper::imitateTabs('end');
    AdminUIHelper::endAdminArea();
    ?>


