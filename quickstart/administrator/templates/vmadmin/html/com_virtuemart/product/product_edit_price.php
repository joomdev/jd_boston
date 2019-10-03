<?php
/**
 *
 * Main product information
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2012 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_price.php 10002 2018-12-18 10:06:22Z alatak $
 * http://www.seomoves.org/blog/web-design-development/dynotable-a-jquery-plugin-by-bob-tantlinger-2683/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>


<?php
$prices = array();
if ($this->product->allPrices) {
	$prices = $this->product->allPrices;
}
$debug = true;
$min = ".min";
if ($debug) {
	$min = '';
}
$class="vm-chzn-select vm-drop";
?>

<script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.5.2/vue<?php echo $min ?>.js"></script>
<!-- CDNJS :: Sortable (https://cdnjs.com/) -->
<script src="//cdn.jsdelivr.net/npm/sortablejs@1.7.0/Sortable<?php echo $min ?>.js"></script>
<!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.17.0/vuedraggable<?php echo $min ?>.js"></script>

<div id="productPrices">
	<button @click.prevent="addPrice" class="btn btn-mini button btn-success">
		<span class="icon-plus"></span>
	</button>


	<div v-for="(price, index) in prices" class=" well nr-well">


		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<label class="hasPopover"
							   data-content="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST') ?></label>
					</div>
					<div class="controls">
						<input type="text"
							   v-model="price.costPrice"
							   :name="'mprices[product_price][' + index + ']'"
						/>

						<input type="hidden"
							   v-model="price.virtuemart_product_price_id"
							   :name="'mprices[virtuemart_product_price_id][' + index + ']'"
						/>

						<select v-model="price.product_currency"
								:name="'mprices[product_currency]'"
						>

							<option v-for="currency in currencies"
									:value="currency.virtuemart_currency_id">
								{{ currency.currency_name }}
							</option>
						</select>
					</div>
				</div>

				<!-- COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE -->
				<div class="control-group">
					<div class="control-label">
						<label class="hasPopover"
							   data-content="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE') ?></label>
					</div>
					<div class="controls">
						<input type="text"
							   readonly
							   class="inputbox readonly"
							   v-model="price.basePrice"
							   :name="'mprices[basePrice][' + index + ']'"
						/>
						<?php echo $this->vendor_currency_symb; ?>


						<select v-model="price.product_tax_id"
								:name="'mprices[product_tax_id][' + index + ']'"
						>
							<option :value="-1">
								<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_TAX_NONE') ?>
							</option>
							<option :value="0">
								<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_TAX_NO_SPECIAL') ?>
							</option>
							<option v-for="taxRate in taxRates"
									:value="taxRate.virtuemart_calc_id">
								{{ taxRate.calc_name }}
							</option>
						</select>

						<span class="hasPopover"
							  data-content="<?php echo vmText::_('COM_VIRTUEMART_RULES_EFFECTING_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_TAX_EFFECTING') ?></span>
						<ul>
							<li v-for="taxRule in taxRules[index]">
								{{ taxRule }}
							</li>
						</ul>

					</div>
				</div>

				<!-- COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL -->
				<div class="control-group">
					<div class="control-label">
						<label class="hasPopover"
							   data-content="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL') ?></label>
					</div>
					<div class="controls">
						<input type="text"
							   v-model="price.salesPriceTemp"
							   :name="'mprices[salesPrice][' + index + ']'"
						/>
						<?php echo $this->vendor_currency_symb; ?>


						<select v-model="price.product_discount_id"
								:name="'mprices[product_discount_id][' + index + ']'"
						>
							<option :value="-1">
								<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_NONE') ?>
							</option>
							<option :value="0">
								<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_NO_SPECIAL') ?>
							</option>
							<option v-for="discount in discounts"
									:value="discount.virtuemart_calc_id">
								{{ discount.calc_name }}
							</option>
						</select>

						<div v-if="DBTaxRules[index]|| DATaxRules[index] ">
							<?php echo vmText::_('COM_VIRTUEMART_RULES_EFFECTING') ?>
							<ul>
								<li v-for="DBTaxRule in DBTaxRules[index]">
									{{ DBTaxRule }}
								</li>
							</ul>
							<ul v-if="DATaxRules[index]">
								<li v-for="DATaxRule in DATaxRules[index]">
									{{ DATaxRule }}
								</li>
							</ul>
						</div>

					</div>
				</div>

				<!-- COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE -->
				<div class="control-group">
					<div class="control-label">
						<label class="hasPopover"
							   data-content="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE') ?></label>
					</div>
					<div class="controls">
						<input type="text"
							   v-model="price.product_override_price"
							   :name="'mprices[product_override_price][' + index + ']'"
						/>
						<?php echo $this->vendor_currency_symb; ?>

						<label>
							<input
									type="checkbox"
									v-model="price.use_desired_price"
									:name="'mprices[use_desired_price][' + index + ']'"

							>
							<span class="hasPopover"
								  data-content="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL') ?></span>

						</label>


						<?php
						// 							echo VmHtml::checkbox('override',$this->product->override);
						$overrideOptions = array(
							array('value' => 0, 'text' => vmText::_('COM_VIRTUEMART_DISABLED')),
							array('value' => 1, 'text' => vmText::_('COM_VIRTUEMART_OVERWRITE_FINAL')),
							array('value' => -1, 'text' => vmText::_('COM_VIRTUEMART_OVERWRITE_PRICE_TAX')));
						?>
						<div v-for="overrideOption in overrideOptions">
							<label>
								<input type="radio"
									   v-model="price.override"
									   :value="overrideOptions.value"
									   :name="'mprices[override][' + index + ']'">
								{{ overrideOption.text }}
							</label>
						</div>


					</div>
				</div>

			</div>


			<div class="span5">
				<div class="control-group">
					<div class="control-label">
						<label class="hasPopover"
							   data-content="<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP_PRICE_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP') ?></label>
					</div>
					<div class="controls">

						<select v-model="price.virtuemart_shoppergroup_id"
								:name="'mprices[virtuemart_shoppergroup_id][' + index + ']'"
						>
							<option value="">
								<?php echo vmText::_('COM_VIRTUEMART_DRDOWN_AVA2ALL') ?>
							</option>
							<option v-for="shopperGroup in shopperGroups"
									:value="shopperGroup.virtuemart_shoppergroup_id">
								{{ shopperGroup.shopper_group_name }}
							</option>
						</select>
					</div>
				</div>


				<div class="control-group">
					<div class="control-label">
						<?php
						echo vmText::_('COM_VIRTUEMART_PRODUCT_PRICE_DATE_RANGE');
						?>
					</div>
					<div class="controls">
						<?php echo vmJsApi::jDate($this->product->allPrices[$this->product->selectedPrice]['product_price_publish_up'], 'mprices[product_price_publish_up][]'); ?>
						<?php echo vmJsApi::jDate($this->product->allPrices[$this->product->selectedPrice]['product_price_publish_down'], 'mprices[product_price_publish_down][]'); ?>
					</div>
				</div>


				<div class="control-group">
					<div class="control-label">
						<?php
						echo vmText::_('COM_VIRTUEMART_PRODUCT_PRICE_QUANTITY_RANGE');
						?>
					</div>
					<div class="controls">
						<input type="text"
							   v-model="price.price_quantity_start"
							   :name="'mprices[price_quantity_start][' + index + ']'"
						/>
						<input type="text"
							   v-model="price.price_quantity_end"
							   :name="'mprices[price_quantity_end][' + index + ']'"
						/>
					</div>
				</div>


			</div>
			<div class="span1">
				<div class="control-group">
					<button @click.prevent="deletePrice" class="btn btn-mini button btn-danger">
						<span class="icon-minus"></span>
					</button>
					<button @click.prevent="movePrice" class="btn btn-mini button btn-info">
						<span class="icon-move"></span>
					</button>
				</div>


			</div>
		</div>


	</div>


</div>
<script>
  var vm = new Vue({
    el: '#productPrices',
    data: {
      prices:<?php echo json_encode($prices) ?>,
      currencies:<?php echo json_encode($this->currencies) ?>,
      discounts:<?php echo json_encode($this->discounts) ?>,
      taxRates:<?php echo json_encode($this->taxrates) ?>,
      taxRules:<?php echo json_encode($this->taxRules) ?>,
      DATaxRules:<?php echo json_encode($this->DATaxRules) ?>,
      DBTaxRules:<?php echo json_encode($this->DBTaxRules) ?>,
      overrideOptions:<?php echo json_encode($overrideOptions) ?>,
      shopperGroups:<?php echo json_encode($this->shopperGroups) ?>,

      //

      newPrice: {
        product_price: '',
        virtuemart_product_price_id: 0,
        product_currency: '<?php echo $this->vendor->vendor_currency ?>',
        price_quantity_start: null,
        price_quantity_end: null,
        product_price_publish_up: null,
        product_price_publish_down: null,
        product_tax_id: 0,
        product_discount_id: 0,
        product_override_price: '',
        override: null,
        virtuemart_shoppergroup_id: '',
        use_desired_price: '',
        salesPrice: '',
      },
    },
    methods: {
      deletePrice: function (price) {
        this.prices.splice(this.prices.indexOf(price), 1)
      },
      addPrice: function () {
        this.prices.push({
          product_price: this.newPrice.product_price,
          virtuemart_product_price_id: this.newPrice.virtuemart_product_price_id,
          product_currency: this.newPrice.product_currency,
          price_quantity_start: this.newPrice.price_quantity_start,
          price_quantity_end: this.newPrice.price_quantity_end,
          product_price_publish_up: this.newPrice.product_price_publish_up,
          product_price_publish_down: this.newPrice.product_price_publish_down,
          product_tax_id: this.newPrice.product_tax_id,
          product_discount_id: this.newPrice.product_discount_id,
          product_override_price: this.newPrice.product_override_price,
          override: this.newPrice.override,
          virtuemart_shoppergroup_id: this.newPrice.virtuemart_shoppergroup_id,
          use_desired_price: this.newPrice.use_desired_price,
          salesPrice: this.newPrice.salesPrice,
        })
      },
    }
  })
</script>




