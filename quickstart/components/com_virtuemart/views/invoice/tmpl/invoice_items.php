<?php
/**
*
* Order items view
*
* @package	VirtueMart
* @subpackage Orders
* @author Max Milbers, Valerie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details_items.php 5432 2012-02-14 02:20:35Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$colspan=8;

if ($this->doctype != 'invoice') {
    $colspan -= 4;
} elseif ( ! VmConfig::get('show_tax')) {
    $colspan -= 1;
}

$discountsBill = $this->discountsBill;
$taxBill = $this->taxBill;
 ?>

<table class="html-email" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr style="text-align: left;" class="sectiontableheader">
		<td style="text-align: left;width: 10%;"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') ?></strong></td>
		<td style="text-align: left;width: 30%;" colspan="2"><strong><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_NAME_TITLE') ?></strong></td>
		<td style="text-align: center;width: 10%"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_STATUS') ?></strong></td>
		<?php if ($this->doctype == 'invoice') { ?>
		<td style="text-align: right;width: 10%"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRICE') ?></strong></td>
		<?php } ?>
		<td style="text-align: right;width: 6%"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_QTY') ?></strong></td>
		<?php if ($this->doctype == 'invoice') { ?>
			<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;width: 11%;"><strong><?php
				if(is_array($taxBill) and count($taxBill)==1){
					reset($taxBill);
					$t = current($taxBill);
					echo shopFunctionsF::getTaxNameWithValue($t->calc_rule_name,$t->calc_value);
				} else {
					echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_TAX');
				}
		?></strong></td>
			<?php } ?>
		<td style="text-align: right;width: 11%;"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL_DISCOUNT_AMOUNT') ?></strong></td>
		<td style="text-align: right;width: 12%;"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></strong></td>
		<?php } ?>
	</tr>

<?php
$menuItemID = shopFunctionsF::getMenuItemId($this->orderDetails['details']['BT']->order_language);

VirtueMartModelCustomfields::$useAbsUrls = ($this->isMail or $this->isPdf);
foreach($this->orderDetails['items'] as $item) {
	$qtt = $item->product_quantity ;

    if ($this->print and !$this->isPdf) {
		$product_name = $item->order_item_name;;
	} else {
		$product_name = '<a href="'.JURI::root().'index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id .
		'&virtuemart_product_id=' . $item->virtuemart_product_id . '&Itemid=' . $menuItemID.'">'.$item->order_item_name.'</a>';
	}

	?>
	<tr style="vertical-align: top;">
		<td style="text-align: left;">
			<?php echo $item->order_item_sku; ?>
		</td>
		<td style="text-align: left;" colspan="2">
			<div float="right" ><?php echo $product_name; ?></div>
			<?php
				$product_attribute = VirtueMartModelCustomfields::CustomsFieldOrderDisplay($item,'FE');
				echo $product_attribute;
			?>
		</td>
		<td style="text-align: center;">
			<?php echo $this->orderstatuses[$item->order_status]; ?>
		</td>
	<?php if ($this->doctype == 'invoice') { ?>
		<td style="text-align: right;" class="priceCol">
			<?php
			$item->product_discountedPriceWithoutTax = (float) $item->product_discountedPriceWithoutTax;
			if (!empty($item->product_priceWithoutTax) && $item->product_discountedPriceWithoutTax != $item->product_priceWithoutTax) {
				echo '<span style="text-decoration: line-through;">'.$this->currency->priceDisplay($item->product_item_price, $this->user_currency_id) .'</span><br />';
				echo '<span >'.$this->currency->priceDisplay($item->product_discountedPriceWithoutTax, $this->user_currency_id) .'</span><br />';
			} else {
				echo '<span >'.$this->currency->priceDisplay($item->product_item_price, $this->user_currency_id) .'</span><br />';
			}
			?>
		</td>
	<?php } ?>
		<td style="text-align: right;">
			<?php echo $qtt; ?>
		</td>
	<?php if ($this->doctype == 'invoice') { ?>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;" class="priceCol"><?php echo "<span  class='priceColor2'>".$this->currency->priceDisplay($item->product_tax ,$this->user_currency_id, $qtt)."</span>" ?></td>
		<?php } ?>
		<td style="text-align: right;" class="priceCol" >
			<?php echo  $this->currency->priceDisplay( $item->product_subtotal_discount, $this->user_currency_id );  //No quantity is already stored with it ?>
		</td>
		<td style="text-align: right;" class="priceCol">
			<?php
			$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
			$class = '';
			if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
				echo '<span style="text-decoration: line-through;">'.$this->currency->priceDisplay($item->product_basePriceWithTax,$this->user_currency_id,$qtt) .'</span><br />' ;
			}
			elseif (empty($item->product_basePriceWithTax) && $item->product_item_price != $item->product_final_price) {
				echo '<span style="text-decoration: line-through;">' . $this->currency->priceDisplay($item->product_item_price,$this->user_currency_id,$qtt) . '</span><br />';
			}

			echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,$this->user_currency_id); //No quantity or you must use product_final_price ?>
		</td>
	<?php } ?>
	</tr>
<?php
} ?>

<?php if ($this->doctype == 'invoice') { ?>
	<tr><td colspan="<?php echo $colspan ?>">&nbsp;</td></tr>
	<tr class="sectiontableentry1">
		<td colspan="6" style="text-align: right;"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;"><?php echo "<span  class='priceColor2'>".$this->currency->priceDisplay($this->orderDetails['details']['BT']->order_tax, $this->user_currency_id)."</span>" ?></td>
		<?php } ?>
		<td style="text-align: right;"><?php echo "<span  class='priceColor2'>".$this->currency->priceDisplay($this->orderDetails['details']['BT']->order_discountAmount, $this->user_currency_id)."</span>" ?></td>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_salesPrice, $this->user_currency_id) ?></td>
	</tr>

	<?php
	if ($this->orderDetails['details']['BT']->coupon_discount <> 0.00) {
		$coupon_code=$this->orderDetails['details']['BT']->coupon_code?' ('.$this->orderDetails['details']['BT']->coupon_code.')':'';
	?>
	<tr>
		<td style="text-align: right;" class="pricePad" colspan="6"><?php echo vmText::_('COM_VIRTUEMART_COUPON_DISCOUNT').$coupon_code ?></td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;">&nbsp;</td>
		<?php } ?>
		<td style="text-align: right;">&nbsp;</td>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->coupon_discount, $this->user_currency_id); ?></td>
	</tr>
	<?php } ?>

	<?php
	if($discountsBill){
		foreach($discountsBill as $rule){ ?>
	<tr >
		<td colspan="6" style="text-align: right;" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;">&nbsp;</td>
		<?php } ?>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id); ?></td>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id); ?></td>
	</tr>
			<?php
		}
	} ?>

	<?php
	foreach($this->orderDetails['calc_rules'] as $rule){
		if ($rule->calc_kind== 'DBTaxRulesBill') { ?>
            <tr>
                <td colspan="6"  style="text-align: right;" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
                    <td style="text-align: right;">&nbsp;</td>
				<?php } ?>
                <td style="text-align: right;"><?php echo  $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id);  ?> </td>
                <td style="text-align: right;"><?php echo  $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id);  ?> </td>
            </tr>
			<?php
		} elseif ($rule->calc_kind == 'taxRulesBill') { ?>
            <tr>
                <td colspan="6"  style="text-align: right;" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
                    <td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id); ?> </td>
				<?php } ?>
                <td style="text-align: right;">&nbsp;</td>
                <td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id); ?> </td>
            </tr>
			<?php
		} elseif ($rule->calc_kind == 'DATaxRulesBill') { ?>
            <tr>
                <td colspan="6" style="text-align: right;" class="pricePad"><?php echo $rule->calc_rule_name ?> </td>
				<?php if ( VmConfig::get('show_tax')) { ?>
                    <td style="text-align: right;">&nbsp;</td>
				<?php } ?>
                <td style="text-align: right;"><?php  echo   $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id);  ?> </td>
                <td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount,$this->user_currency_id);  ?> </td>
            </tr>
			<?php
		}
	} ?>

	<tr>
		<td style="text-align: right;" class="pricePad" colspan="6"><?php echo $this->orderDetails['shipmentName'] ?></td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;"><span class='priceColor2'><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_shipment_tax, $this->user_currency_id) ?></span> </td>
		<?php } ?>
		<td style="text-align: right;">&nbsp;</td>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_shipment + $this->orderDetails['details']['BT']->order_shipment_tax, $this->user_currency_id); ?></td>
	</tr>

	<tr>
		<td style="text-align: right;" class="pricePad" colspan="6"><?php echo $this->orderDetails['paymentName'] ?></td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;"><span class='priceColor2'><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_payment_tax, $this->user_currency_id) ?></span> </td>
		<?php } ?>
		<td style="text-align: right;">&nbsp;</td>
		<td style="text-align: right;"><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_payment + $this->orderDetails['details']['BT']->order_payment_tax, $this->user_currency_id); ?></td>
	</tr>

	<tr>
		<td style="text-align: right;" class="pricePad" colspan="6"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></strong></td>
		<?php if ( VmConfig::get('show_tax')) { ?>
		<td style="text-align: right;"><span class='priceColor2'><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_billTaxAmount, $this->user_currency_id); ?></span></td>
		<?php } ?>
		<td style="text-align: right;"><span class='priceColor2'><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_billDiscountAmount, $this->user_currency_id); ?></span></td>
		<td style="text-align: right;"><strong><?php echo $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_total, $this->user_currency_id); ?></strong></td>
	</tr>

	<?php
	$colspan_pcr = 3;
	if (!VmConfig::get('show_tax')) {
		$colspan_pcr -= 1;
	}
	if($this->doVendor){
		$comp = $this->orderDetails['details']['BT']->order_currency;
	} else {
		$comp = $this->user_currency_id;
	}
	if(!empty($this->orderDetails['details']['BT']->payment_currency_rate)
		and $this->orderDetails['details']['BT']->payment_currency_id!=$comp and $this->orderDetails['details']['BT']->payment_currency_rate!=1.0){
	?>
	<tr>
		<td style="text-align: right;" class="pricePad" colspan="6"><strong><?php echo vmText::_('COM_VM_TOTAL_IN_PAYMENT_CURRENCY') ?></strong></td>
		<td style="text-align: right;" class="pricePad" colspan="<?php echo $colspan_pcr ?>"><?php
			if($this->orderDetails['details']['BT']->order_currency==$this->orderDetails['details']['BT']->user_currency_id and $this->orderDetails['details']['BT']->user_currency_id!=$this->orderDetails['details']['BT']->payment_currency_id){
				echo $this->orderDetails['details']['BT']->payment_currency_rate;
			} else if ($this->orderDetails['details']['BT']->order_currency==$this->orderDetails['details']['BT']->payment_currency_id and $this->orderDetails['details']['BT']->payment_currency_id!=$this->orderDetails['details']['BT']->user_currency_id){
				echo $this->orderDetails['details']['BT']->user_currency_rate;
			}
			echo ' <strong>';
			echo $this->currencyP->priceDisplay($this->orderDetails['details']['BT']->order_total, $this->orderDetails['details']['BT']->payment_currency_id); ?>
			</strong></td>
	</tr>
	<?php
	}

	if($taxBill){
		?>
		<tr>
			<td colspan="7" style="text-align: right;" class="pricePad"><?php echo vmText::_('COM_VIRTUEMART_TOTAL_INCL_TAX') ?> </td>
			<?php if ( VmConfig::get('show_tax')) {  ?>
			<td>&nbsp;</td>
			<?php } ?>
			<td>&nbsp;</td>
		</tr><?php
		foreach($taxBill as $rule){
			if ($rule->calc_kind == 'taxRulesBill' or $rule->calc_kind == 'VatTax' ) { ?>
				<tr >
					<td colspan="6" style="text-align: right;" class="pricePad"><?php echo $rule->label ?> </td>
					<?php if ( VmConfig::get('show_tax')) { ?>
					<td style="text-align: right;"><?php echo $this->currency->priceDisplay($rule->calc_amount, $this->user_currency_id); ?></td>
					<?php } ?>
					<td style="text-align: right;">&nbsp;</td>
					<td style="text-align: right;">&nbsp;</td>
				</tr>
				<?php
			}
		}
	}

    ?>   <tr style="border-top-style:double">
            <td align="left" colspan="3" style="padding-right: 5px;"><strong><?php echo vmText::_('COM_VM_ORDER_BALANCE') ?></strong></td>

	<?php
	$this->orderbt = $this->orderDetails['details']['BT'];
	$tp = '';
	$detail=false;
	if (empty($this->orderbt->paid) ) {
		$t = vmText::_('COM_VM_ORDER_UNPAID');
	} else if($this->orderbt->paid == $this->orderbt->toPay){
		$t =  vmText::_('COM_VM_ORDER_PAID');
	} else if($this->orderbt->paid < $this->orderbt->toPay){
		$t =  vmText::sprintf('COM_VM_ORDER_PARTIAL_PAID',$this->orderbt->paid);
		$detail=true;
	} else {
		$t =  vmText::sprintf('COM_VM_ORDER_CREDIT_BALANCE',$this->orderbt->paid);
		$detail=true;
	}
	$trOpen = true;
	$colspan = '5';
	if(empty($this->toRefund) and !$detail){
		echo '<td align="left" colspan="3" style="padding-right: 5px;">'.$t.'</td>';
		//echo '<td><input class="orderEdit" type="text" size="8" name="item_id[paid]" value="'.$this->orderbt->paid.'"/></td>';
		echo '</tr>';
		$trOpen = false;
	}

	if(!empty($this->toRefund)){
		echo '<td colspan="5">'.vmText::_('COM_VM_ORDER_PRODUCTS_TO_REFUND').'</td>';
		if($trOpen) {
			echo '</tr>';
			$trOpen = false;
		}
		foreach ($this->toRefund as $index => $item) {

			$tmpl = "refund-tmpl-" . $index;

			echo '<tr id="'.$tmpl.'" class="order-item">';
			echo '<td colspan="3"></td>';
			echo '<td colspan="2">'.$item->order_item_name.'</td>';
			echo '<td colspan="1">'.$item->order_item_sku.'</td>';
			echo '<td style="text-align: right;" colspan="1">'.$this->currency->priceDisplay($item->product_tax).'</td>';
			echo '<td colspan="1"></td>';
			echo '<td style="text-align: right;" colspan="1">'.$this->currency->priceDisplay($item->product_subtotal_with_tax).'</td>';
			echo '</tr>';
			$this->orderbt->order_total -= $item->product_subtotal_with_tax;
			$colspan = '5';
		}
	} else {
		$colspan = '2';
	}

	if(!empty($this->toRefund) or $detail){

		if($this->orderbt->paid < $this->orderbt->toPay){

			if (empty($this->orderbt->paid)){
				$t =  vmText::_('COM_VM_ORDER_UNPAID');
			} else {
				$t =  vmText::_('COM_VM_ORDER_PARTIAL_PAID');
			}
			$l = vmText::_('COM_VM_ORDER_OUTSTANDING_AMOUNT');

		} else {
			$t =  vmText::_('COM_VM_ORDER_PAID');
			$l = vmText::_('COM_VM_ORDER_BALANCE');
		}

		$tp .= '';
		if($this->orderbt->toPay!=$this->orderbt->order_total){
			if(!$trOpen){
				$tp .= '<tr>';
				$trOpen= true;
			}
			$tp .= '<td colspan="'.$colspan.'"></td>';
			$tp .= '<td style="text-align: right;" colspan="3" style="padding-right: 5px;">'.vmText::_('COM_VM_ORDER_NEW_TOTAL').'</td>';
			$tp .= '<td style="text-align: right;">'.$this->currency->priceDisplay($this->orderbt->toPay).'</td>';

			if($trOpen) {
				$tp .= '</tr>';
				$trOpen = false;
			}
		}

		if(!$trOpen){
			$tp .= '<tr>';
			$trOpen= true;
		}
		$tp .= '<td colspan="'.$colspan.'"></td>';
		$tp .= '<td style="text-align: right;" colspan="3" style="padding-right: 5px;">'.$t.'</td>';
		$tp .= '<td style="text-align: right;" >'.$this->currency->priceDisplay($this->orderbt->paid).'</td>';
		$tp .= '</tr>';

		$tp .= '<tr>';
		$tp .= '<td colspan="5"></td>';
		$tp .= '<td style="text-align: right;" colspan="3" style="padding-right: 5px;">'.$l.'</td>';
		$tp .= '<td style="text-align: right;" >'.$this->currency->priceDisplay(abs($this->orderbt->order_total - $this->orderbt->paid) ).'</td>';
		echo $tp;
	}
	if($trOpen) {
		echo '</tr>';
		$trOpen = false;
	}

} ?>
</table>
