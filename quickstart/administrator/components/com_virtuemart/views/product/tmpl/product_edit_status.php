<?php
/**
*
* Information regarding the product status
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_status.php 10317 2020-05-07 10:03:46Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>
<fieldset>
				<legend><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_STATUS_LBL'); ?></legend>
<table class="adminform" width="100%">
	<tr class="row0">
		<th style="text-align:right;" width="25%">
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_IN_STOCK') ?>
		</th>
		<td width="20%">
			<input  type="text" class="inputbox js-change-stock"  name="product_in_stock" value="<?php echo $this->product->product_in_stock; ?>" size="10" />

            <?php if($this->product->product_parent_id!=0 and !$this->product_childs){
                echo '<span class="hasTooltip" title="'.vmText::_('COM_VM_PRODUCT_FORM_STOCK_SHARED_TIP').'">';
				echo VmHtml::checkbox('shared_stock',$this->product->shared_stock);
				echo vmText::_('COM_VM_PRODUCT_FORM_STOCK_SHARED').'</span>';
            } ?>

        </td>
 		<th style="text-align:right;" width="20%">
			<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_ORDERED_STOCK') ?>
		</th>
		<td colspan="1">
			<input type="text" class="inputbox js-change-stock"  name="product_ordered" value="<?php echo $this->product->product_ordered; ?>" size="10" />
		</td>
	</tr>
	<tr class="row1">
	<!-- low stock notification -->
		<th style="text-align:right;">
		<?php echo vmText::_('COM_VIRTUEMART_LOW_STOCK_NOTIFICATION'); ?>
			</th>
		<td>
			<input type="text" class="inputbox" name="low_stock_notification" value="<?php echo $this->product->low_stock_notification; ?>" size="3" />
		</td>
		<th style="text-align:right;">
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_STEP_ORDER') ?>
		</th>
		<td>
			<input type="text" class="inputbox"  name="step_order_level" value="<?php echo $this->product->step_order_level; ?>" size="10" />
		</td>
	<!-- end low stock notification -->
	</tr>
	<tr class="row0">
		<th style="text-align:right;">
				<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_MIN_ORDER') ?>
</th>
		<td>
			<input type="text" class="inputbox"  name="min_order_level" value="<?php echo $this->product->min_order_level; ?>" size="10" />
		</td>
		<th style="text-align:right;">

				<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_MAX_ORDER') ?>
		</th>
		<td>
			<input type="text" class="inputbox"  name="max_order_level" value="<?php echo $this->product->max_order_level; ?>" size="10" />
		</td>
	</tr>
	<?php if(VmConfig::get('stockhandle_products',false)){ ?>
		<tr class="row1">
			<th style="text-align:right;">
				<?php echo vmText::_('COM_VIRTUEMART_CFG_POOS_ENABLE') ?>
			</th>
			<td colspan="3">
				<?php
				$options = array(
				'0' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_GLOBAL'),
				'none' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_NONE'),
				'disableit' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_IT'),
				'disableit_children' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_IT_CHILDREN'),
				'disableadd' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_ADD'),
				'risetime' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_RISE_AVATIME')
				);
				echo VmHTML::selectList('product_stockhandle', $this->product->product_stockhandle, $options);
				?>
			</td>
		</tr>
	<?php } ?>

	<tr class="row0">
		<th style="text-align:right;">
			<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABLE_DATE') ?>
		</th>
		<td>
			<?php echo vmJsApi::jDate($this->product->product_available_date, 'product_available_date'); ?>
		</td>
	</tr>
	<tr class="row1">
		<th style="text-align:right;">

				<?php echo vmText::_('COM_VIRTUEMART_AVAILABILITY') ?>
		</th>
		<td colspan="2">
			<input type="text" class="inputbox" id="product_availability" name="product_availability" value="<?php echo $this->product->product_availability; ?>" />
			<span class="icon-nofloat vmicon vmicon-16-info tooltip" title="<?php echo '<b>'.vmText::_('COM_VIRTUEMART_AVAILABILITY').'</b><br/ >'.vmText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP1') ?>"></span>

			<?php echo JHtml::_('list.images', 'image', $this->product->product_availability, " ", $this->imagePath); ?>
			<span class="icon-nofloat vmicon vmicon-16-info tooltip" title="<?php echo '<b>'.vmText::_('COM_VIRTUEMART_AVAILABILITY').'</b><br/ >'.vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP2',  $this->imagePath ) ?>"></span>
		</td>
		<td>
		    <img border="0" id="imagelib" alt="<?php echo vmText::_('COM_VIRTUEMART_PREVIEW'); ?>" name="imagelib" src="<?php if ($this->product->product_availability and file_exists(JPATH_ROOT . '/' . $this->imagePath.$this->product->product_availability)) echo JURI::root(true).$this->imagePath.$this->product->product_availability;?>"/>
		</td>
	</tr>
</table>
</fieldset>

<fieldset>
	<legend><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_SHOPPERS'); ?></legend>
		<?php echo $this->loadTemplate('customer'); ?>
</fieldset>



