<?php
/**
*
* Set the product dimensions
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD, Valérie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_dimensions.php 10002 2018-12-18 10:06:22Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_DIM_WEIGHT_LBL'); ?></h4>
	<div class="well-desc"></div>

	<div class="control-group" style="margin-top:10px;">
		<div class="control-label">
			<?php
			echo vmText::_('COM_VIRTUEMART_PRODUCT_LENGTH') ;
			?>
		</div>
		<div class="controls">
			<input type="text" class="inputbox"  name="product_length" value="<?php echo $this->product->product_length; ?>" size="15" maxlength="15" />   <?php echo " ".$this->lists['product_lwh_uom'];?>
		</div>
	</div>

	<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_WIDTH', 'product_width', $this->product->product_width, '', '', 15, 15); ?>
	<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_HEIGHT', 'product_height', $this->product->product_height, '', '', 15, 15); ?>

	<div class="control-group"  >
		<div class="control-label">
			<?php
			echo vmText::_('COM_VIRTUEMART_PRODUCT_WEIGHT') ;
			?>
		</div>
		<div class="controls">
			<input type="text" class="inputbox"  name="product_weight" value="<?php echo $this->product->product_weight; ?>" size="15" maxlength="15" />   <?php echo " ".$this->lists['product_weight_uom'];?>
		</div>
	</div>

	<div class="control-group" >
		<div class="control-label">
			<?php
			echo vmText::_('COM_VIRTUEMART_PRODUCT_PACKAGING') ;
			?>
		</div>
		<div class="controls">
			<input type="text" class="inputbox"  name="product_packaging" value="<?php echo $this->product->product_packaging; ?>" size="15" maxlength="15" />   <?php echo " ".$this->lists['product_iso_uom'];?>
		</div>
	</div>

	<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_BOX', 'product_box', $this->product->product_box, '', '', 15, 15); ?>


</div>

