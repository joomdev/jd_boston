<?php
/**
 *
 * Handle the Product Custom Fields
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author RolandD, Patrick khol, Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$i = 0;
$customfields = array('categories' => array(), 'products' => array(), 'fields' => array(), 'customPlugins' => array(),);
if (isset($this->product->customfields)) {
	$customfieldsModel = VmModel::getModel('customfields');


	$i = 0;

	foreach ($this->product->customfields as $k => $customfield) {

		//vmdebug('displayProductCustomfieldBE',$customfield);

		$customfield->display = $customfieldsModel->displayProductCustomfieldBE($customfield, $this->product, $i);

		if ($customfield->is_cart_attribute) {
			$customfield->cartIcon = 'default';
		} else {
			$customfield->cartIcon = 'default-off';
		}
		if ($customfield->field_type == 'Z') {
			// R: related categories
			$customfields['categories'][] = $customfield;

		} elseif ($customfield->field_type == 'R') {
			// R: related products
			$customfields['products'] [] = $customfield;

		} else {

			$customfields['fields'][] = $customfield;

		}
		$i++;
	}
}


?>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_RELATED_CATEGORIES'); ?></h4>
	<div class="well-desc"></div>
	<?php echo vmText::_('COM_VIRTUEMART_CATEGORIES_RELATED_SEARCH'); ?>
	<div class="jsonSuggestResults relatedcategoriesSearch">
		<input type="text" size="40" name="search" id="relatedcategoriesSearch" value=""/>
		<button class="reset-value btn"><?php echo vmText::_('COM_VIRTUEMART_RESET') ?></button>
	</div>
	<div id="custom_categories" class="ui-sortable">
		<?php
		foreach ($customfields['categories'] as $index => $customfield) {
			?>
			<div class="vm_thumb_image">
				<span class="vmicon vmicon-16-move"></span>
				<div class="vmicon vmicon-16-remove 4remove"></div>
				<div><?php echo $customfield->display ?></div>
				<?php echo VirtueMartModelCustomfields::setEditCustomHidden($customfield, $index) ?>
			</div>
			<?php
		}
		?>
	</div>
</div>


<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h4>
	<div class="well-desc"></div>
	<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_RELATED_SEARCH'); ?>
	<div class="jsonSuggestResults relatedproductsSearch">
		<input type="text" size="40" name="search" id="relatedproductsSearch" value=""/>
		<button class="reset-value btn"><?php echo vmText::_('COM_VIRTUEMART_RESET') ?></button>
	</div>
	<div id="custom_products" class="ui-sortable">
		<?php
		foreach ($customfields['products'] as $index => $customfield) {
			?>
			<div class="vm_thumb_image">
				<span class="vmicon vmicon-16-move"></span>
				<div class="vmicon vmicon-16-remove 4remove"></div>
				<div><?php echo $customfield->display ?></div>
				<?php echo VirtueMartModelCustomfields::setEditCustomHidden($customfield, $index) ?>
			</div>
			<?php
		}
		?>
	</div>
</div>


<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_CUSTOM_FIELD_TYPE'); ?></h4>
	<div class="well-desc"></div>
	<?php echo $this->customsList; ?>

	<div id="custom_fields" class="ui-sortable">
		<?php

		foreach ($customfields['fields'] as $index => $customfield) {
			$checkValue = $customfield->virtuemart_customfield_id;
			$title = '';
			$text = '';
			if (isset($this->fieldTypes[$customfield->field_type])) {
				$type = $this->fieldTypes[$customfield->field_type];
			} else {
				$type = 'deprecated';
			}

			if ($customfield->override != 0 or $customfield->disabler != 0) {

				if (!empty($customfield->disabler)) {
					$checkValue = $customfield->disabler;
				}
				if (!empty($customfield->override)) {
					$checkValue = $customfield->override;
				}
				$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_OVERRIDE', $checkValue);
				if ($customfield->disabler != 0) {
					$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_DISABLED', $checkValue);
				}

				if ($customfield->override != 0) {
					$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_OVERRIDE', $checkValue);
				}

			} else {
				if ($customfield->virtuemart_product_id == $this->product->product_parent_id) {
					$title = vmText::_('COM_VIRTUEMART_CUSTOM_INHERITED') . '</br>';
				}
			}

			?>
			<div class="removable">
				<div>
					<?php echo vmText::_($type) . ' ' . vmText::_($customfield->custom_title) ?>
				</div>
				<div>
					<?php echo $title; ?>
					<?php
					if (!empty($title)) { ?>
						<span class="hasTip"
							  title="<?php echo htmlentities(vmText::_('COM_VIRTUEMART_CUSTOMFLD_DIS_DER_TIP')) ?>">d:<?php echo VmHtml::checkbox('field[' . $i . '][disabler]', $customfield->disabler, $checkValue) ?></span>
						<span class="hasTip"
							  title="<?php echo htmlentities(vmText::_('COM_VIRTUEMART_DIS_DER_CUSTOMFLD_OVERR_DER_TIP')) ?>">o:<?php echo VmHtml::checkbox('field[' . $i . '][override]', $customfield->override, $checkValue) ?></span>
						<?php
					}
					?>
				</div>
				<div>
					<span class="vmicon vmicon-16-<?php echo $customfield->cartIcon ?>"></span>
				</div>
				<?php
				if ($customfield->virtuemart_product_id == $this->product->virtuemart_product_id or $customfield->override != 0) {
					?>
					<span class="vmicon vmicon-16-move"></span>
					<span class="vmicon vmicon-16-remove 4remove"></span>
					<?php
				}
				?>
				<?php echo VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i); ?>
				<?php echo $customfield->display; ?>
			</div>
			<?php
		}
		?>
	</div>
</div>





