<?php
/**
 *
 * Information regarding the product status
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author RolandD, Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_status.php 10002 2018-12-18 10:06:22Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


?>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_STATUS_LBL'); ?></h4>
	<div class="well-desc"></div>
	<div class="row-fluid form-horizontal-desktop">
		<div class="span6">
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_IN_STOCK', 'product_in_stock', $this->product->product_in_stock, 'class="js-change-stock"', '', 15, 15); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_LOW_STOCK_NOTIFICATION', 'product_ordered', $this->product->low_stock_notification, '', '', 15, 15); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_MIN_ORDER', 'min_order_level', $this->product->min_order_level, '', '', 15, 15); ?>
			<?php if (VmConfig::get('stockhandle_products', false)) {
				$options = array(
					'0' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_GLOBAL'),
					'none' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_NONE'),
					'disableit' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_IT'),
					'disableit_children' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_IT_CHILDREN'),
					'disableadd' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_DISABLE_ADD'),
					'risetime' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_POOS_RISE_AVATIME')
				);

				echo VmHTML_override::row('genericlist', 'COM_VIRTUEMART_CFG_POOS_ENABLE', $options, 'product_stockhandle', '', 'value', 'text', $this->product->product_stockhandle);

			 } ?>
			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABLE_DATE');
					?>
				</div>
				<div class="controls">
					<?php echo vmJsApi::jDate($this->product->product_available_date, 'product_available_date'); ?>
				</div>
			</div>

		</div>
		<div class="span6">
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_ORDERED_STOCK', 'product_ordered', $this->product->product_ordered, 'class="js-change-stock"', '', 15, 15); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_STEP_ORDER', 'step_order_level', $this->product->step_order_level, '', '', 15, 15); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_MAX_ORDER', 'max_order_level', $this->product->max_order_level, '', '', 15, 15); ?>
			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_AVAILABILITY');
					?>
				</div>
				<div class="controls">
					<input type="text" class="inputbox" id="product_availability" name="product_availability"
						   value="<?php echo $this->product->product_availability; ?>"/>
					<span class="icon-nofloat vmicon vmicon-16-info tooltip"
						  title="<?php echo '<b>' . vmText::_('COM_VIRTUEMART_AVAILABILITY') . '</b><br/ >' . vmText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP1') ?>"></span>

					<?php echo JHtml::_('list.images', 'image', $this->product->product_availability, " ", $this->imagePath); ?>
					<span class="icon-nofloat vmicon vmicon-16-info tooltip"
						  title="<?php echo '<b>' . vmText::_('COM_VIRTUEMART_AVAILABILITY') . '</b><br/ >' . vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP2', $this->imagePath) ?>"></span>

					<img border="0" id="imagelib" alt="<?php echo vmText::_('COM_VIRTUEMART_PREVIEW'); ?>" name="imagelib"
						 src="<?php if ($this->product->product_availability and file_exists(JPATH_ROOT . '/' . $this->imagePath . $this->product->product_availability)) {
						     echo JURI::root(true) . $this->imagePath . $this->product->product_availability;
					     } ?>"/>

				</div>
			</div>

		</div>
	</div>

</div>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_SHOPPERS'); ?></h4>
	<div class="well-desc"></div>

		<?php echo $this->loadTemplate('customer'); ?>

</div>




