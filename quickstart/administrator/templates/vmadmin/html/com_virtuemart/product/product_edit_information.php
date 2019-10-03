<?php
/**
 *
 * Main product information
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers, Valérie Isaksen
 * @todo Price update calculations
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 10002 2018-12-18 10:06:22Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>

<div class=" well nr-well">
	<h4>
		<?php
		$parentRel = '';
		if ($this->product->product_parent_id) {
			$parentRel = vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_PARENT', JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=' . $this->product->product_parent_id),
					($this->product_parent->product_name), array('title' => vmText::_('COM_VIRTUEMART_EDIT') . ' ' . $this->product_parent->product_name)) . ' =&gt; ');
		}
		echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_INFORMATION', $parentRel);
		echo ' id: ' . $this->product->virtuemart_product_id
		?>
	</h4>
	<div class="well-desc"></div>
	<div class="row-fluid form-horizontal-desktop">
		<div class="span6">
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_NAME', 'product_name', $this->product->product_name, '', '', 32, 255); ?>
			<?php echo $this->origLang ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_SKU', 'product_sku', $this->product->product_sku, '', '', 32, 255); ?>

			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_MANUFACTURER');
					?>
				</div>
				<div class="controls">
					<?php
					echo $this->lists['manufacturers'];
					?>
				</div>
			</div>

			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_CATEGORY_S');
					?>
				</div>
				<div class="controls">
					<select class="vm-drop" id="categories" name="categories[]" multiple="multiple"
							data-placeholder="<?php echo vmText::_('COM_VIRTUEMART_DRDOWN_SELECT_SOME_OPTIONS') ?>">
						<option value="-2" selected="selected">Do not store</option>
					</select>
				</div>
			</div>
			<?php
			// It is important to have all product information in the form, since we do not preload the parent
			// I place the ordering here, maybe we make it editable later.
			if (!isset($this->product->ordering)) {
				$this->product->ordering = 0;
				?><input type="hidden" value="<?php echo $this->product->ordering ?>" name="ordering"> <?php
			} ?>

			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP');
					?>
				</div>
				<div class="controls">
					<?php echo $this->shoppergroupList; ?>
				</div>
			</div>


			<div class="control-group" style="margin-top:10px;">
				<div class="control-label">
					<?php
					echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_CANONICAL_CATEGORY');
					?>
				</div>
				<div class="controls">
					<?php $this->categoryTree = ShopFunctions::categoryListTree($this->product->product_canon_category_id); ?>
					<select class="inputbox" id="product_canon_category_id" name="product_canon_category_id"
							value="<?php echo $this->product->product_canon_category_id ?>" size="10">
						<option value="">No override</option>
						<?php echo $this->categoryTree; ?>
					</select>
				</div>
			</div>
			<?php if ($this->showVendors()) { ?>
				<div class="control-group" style="margin-top:10px;">
					<div class="control-label">
						<?php
						echo vmText::_('COM_VIRTUEMART_VENDOR');
						?>
					</div>
					<div class="controls">
						<?php echo $this->lists['vendors']; ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="span6">
			<?php echo VmHTML_override::row('checkbox', vmText::_('COM_VIRTUEMART_PUBLISHED'), 'published', $this->product->published); ?>
			<?php echo VmHTML_override::row('checkbox', vmText::_('COM_VIRTUEMART_PRODUCT_FORM_SPECIAL'), 'product_special', $this->product->product_special); ?>
			<?php echo VmHTML_override::row('checkbox', vmText::_('COM_VIRTUEMART_PRODUCT_FORM_DISCONTINUED'), 'product_discontinued', $this->product->product_discontinued); ?>

			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_ALIAS', 'slug', $this->product->slug, '', '', 32, 255); ?>

			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_GTIN', 'product_gtin', $this->product->product_gtin, '', '', 32, 255); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_MPN', 'product_mpn', $this->product->product_mpn, '', '', 32, 255); ?>
			<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_FORM_URL', 'product_url', $this->product->product_url, '', '', 32, 255); ?>
		</div>
	</div>
</div>

<?php
//$product = $this->product;

if (empty($this->product->prices)) {
	$this->product->prices[] = array();
}
$this->i = 0;
$rowColor = 0;

$calculator = $this->calculator;
$currency_model = VmModel::getModel('currency');
$this->currencies = $currency_model->getCurrencies();
$this->taxrates = VirtueMartModelCalc::getTaxes();
$this->discounts = VirtueMartModelCalc::getDiscounts();
vmLanguage::loadJLang('com_virtuemart_shoppers', TRUE);
$shopperModel = VmModel::getModel('shoppergroup');
$this->shopperGroups = $shopperModel->getShopperGroups(FALSE, TRUE);


?>

<div class=" well nr-well">
	<h4>
		<?php
		echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_PRICES', $this->activeShoppergroups);
		if ($this->deliveryCountry) {
			echo ' ' . vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_PRICES_COUNTRY', $this->deliveryCountry);
		}
		if ($this->deliveryState) {
			echo ' ' . vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_PRICES_STATE', $this->deliveryState);
		}
		?>
	</h4>
	<div class="well-desc"></div>

	<?php
	$this->taxRules = array();
	$this->DBTaxRules = array();
	$this->DATaxRules = array();
	if ($this->product->allPrices) {
		foreach ($this->product->allPrices as $k => $sPrices) {

			if (empty($this->product->allPrices[$k]['product_currency'])) {
				$this->product->allPrices[$k]['product_currency'] = $this->vendor->vendor_currency;
			}

			$this->product->selectedPrice = $k;
			$this->calculatedPrices = $calculator->getProductPrices($this->product);
			$this->product->allPrices[$k] = array_merge($this->product->allPrices[$k], $this->calculatedPrices);

			$currency_model = VmModel::getModel('currency');
			//$this->lists['currencies'] = JHtml::_('select.genericlist', $currencies, 'mprices[product_currency][]', 'class="' . $class . '"', 'virtuemart_currency_id', 'currency_name', $this->product->allPrices[$k]['product_currency'], '[');

			$DBTax = array();
			$this->DBTaxRules[$k] = array();
			foreach ($calculator->rules['DBTax'] as $rule) {
				$DBTax[] = $rule['calc_name'];
			}
			$this->DBTaxRules[$k] = $DBTax;

			$tax = array();
			$this->taxRules[$k] = array();
			foreach ($calculator->rules['Tax'] as $rule) {
				$tax[] = $rule['calc_name'];
			}
			foreach ($calculator->rules['VatTax'] as $rule) {
				$tax[] = $rule['calc_name'];
			}
			if ($tax) {
				$this->taxRules[$k] = $tax;
			}

			$DATax = array();
			$this->DATaxRules[$k] = array();
			foreach ($calculator->rules['DATax'] as $rule) {
				$DATax[] = $rule['calc_name'];
			}
			$this->DATaxRules[$k] = $DATax;

			if (!isset($this->product->product_tax_id)) {
				$this->product->product_tax_id = 0;
			}
			if (!isset($this->product->allPrices[$k]['product_tax_id'])) {
				$this->product->allPrices[$k]['product_tax_id'] = 0;
			}
			//$this->lists['taxrates'] = ShopFunctions::renderTaxList($this->product->allPrices[$k]['product_tax_id'], 'mprices[product_tax_id][]', 'class="' . $class . '"');
			if (!isset($this->product->allPrices[$k]['product_discount_id'])) {
				$this->product->allPrices[$k]['product_discount_id'] = 0;
			}
			$this->lists['discounts'] = $this->renderDiscountList($this->product->allPrices[$k]['product_discount_id'], 'mprices[product_discount_id][]');

			//$this->lists['shoppergroups'] = ShopFunctions::renderShopperGroupList($this->product->allPrices[$k]['virtuemart_shoppergroup_id'], false, 'mprices[virtuemart_shoppergroup_id][]', 'COM_VIRTUEMART_DRDOWN_AVA2ALL', array('class' => $class));


		}
	}
	echo $this->loadTemplate('price');
	?>


</div>


<?php
if ($this->product->virtuemart_product_id) {
	$link = JRoute::_('index.php?option=com_virtuemart&view=product&task=createChild&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&' . JSession::getFormToken() . '=1');
	$add_child_button = "";
} else {
	$link = "";
	$add_child_button = " not-active";
}
?>

<div class="well nr-well">
	<h4>
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ADD_CHILD') ?>
	</h4>
	<div class="well-desc"></div>
	<div class="button2-left <?php echo $add_child_button ?> btn-wrapper">
		<div class="blank">
			<?php if ($link) { ?>
			<a href="<?php echo $link ?>" class="btn btn-small">
				<?php
				} else {
				?>
				<span class="hasTip" title="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ADD_CHILD_TIP') ?>">
			<?php
			}
			?>
			<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ADD_CHILD'); ?>
			<?php if ($link) { ?>
			</a>
		<?php } else { ?>
			</span>
			<?php
		}
		?>

		</div>
	</div>
</div>


<div class="well nr-well">
	<h4>
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_PARENTID') ?>
	</h4>
	<div class="well-desc"></div>
	<?php echo VmHTML_override::row('input', 'COM_VIRTUEMART_PRODUCT_PARENTID', 'product_parent_id', $this->product->product_parent_id, '', '', 32, 32); ?>

</div>


<div class="well nr-well">
	<h4>
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_PRINT_INTNOTES') ?>
	</h4>
	<div class="well-desc"></div>
	<?php echo VmHTML_override::row('textarea', vmText::_('COM_VIRTUEMART_PRODUCT_PRINT_INTNOTES'), 'intnotes', $this->product->intnotes, 'class="nr-textarea"', 200, 10); ?>

</div>



