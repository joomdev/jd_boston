<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author     Max Milbers
 * @todo       handle child products
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version    $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];

//region logic for order quantity default values
$orderQuantityStartValue = (isset($viewData['init']))
    ? $viewData['init']
    : 1;
$orderQuantityStartValue = (!empty($product->min_order_level) and $orderQuantityStartValue < $product->min_order_level)
    ? $product->min_order_level
    : $orderQuantityStartValue;

$orderQuantityStep = 1;
if (!empty($product->step_order_level))
{
    $orderQuantityStep = $product->step_order_level;
    if (!empty($orderQuantityStartValue))
    {
        if ($orderQuantityStartValue < $orderQuantityStep)
        {
            $orderQuantityStartValue = $orderQuantityStep;
        } else
        {
            $orderQuantityStartValue = ceil($orderQuantityStartValue / $orderQuantityStep) * $orderQuantityStep;
        }
    }
    if (empty($product->min_order_level) and !isset($viewData['init']))
    {
        $orderQuantityStartValue = $orderQuantityStep;
    }
}
$orderQuantityMinimum = ($product->min_order_level > 0)
    ? $product->min_order_level
    : 1;
//endregion


if (!VmConfig::get('use_as_catalog', 0))
{ ?>
    <?php
    // Display the quantity box
    $stockhandle = (VmConfig::get('stockhandle_products', FALSE)
        && $product->product_stockhandle)
        ? $product->product_stockhandle
        : VmConfig::get('stockhandle', 'none');

    if (($stockhandle == 'disableit' or $stockhandle == 'disableadd')
        and ($product->product_in_stock - $product->product_ordered) < $orderQuantityMinimum)
    {
        $linkToNotifyCustomerAboutProductStock = JRoute::_(
            'index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id
        );
        ?>
        <a href="<?php echo $linkToNotifyCustomerAboutProductStock; ?>" rel="nofollow" class="btn btn-success">
            <?php echo vmText::_('COM_VIRTUEMART_CART_NOTIFY') ?>
        </a>
        <?php
    } else if (!(VmConfig::get('askprice', TRUE)
        and empty((float) $product->prices['costPrice'])))
    {
        //region quantity field with in- and decrease button
        if ($product->orderable)
        {
            ?>
            <div data-vm-product="quantity-container" class="input-group mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <?php echo vmText::_('COM_VIRTUEMART_CART_QUANTITY') ?>
                    </span>
                </div>
                <input type="text" class="form-control text-center" name="quantity[]"
                       data-vm-product="quantity-field"
                       data-vm-product-order-quantity-start-value="<?php echo $orderQuantityStartValue; ?>"
                       data-vm-product-order-quantity-step="<?php echo $orderQuantityStep; ?>"
                       data-vm-product-order-quantity-max="<?php echo $product->max_order_level; ?>"
                       data-vm-product-order-quantity-error="<?php echo vmText::_(
                           'COM_VIRTUEMART_WRONG_AMOUNT_ADDED'
                       ); ?>"
                       value="<?php echo $orderQuantityStartValue; ?>">
                <div class="input-group-append">
                    <button type="button" data-vm-product="increase-quantity-btn" class="btn
                    btn-outline-secondary">+
                    </button>
                    <button type="button" data-vm-product="decrease-quantity-btn" class="btn
                    btn-outline-secondary">-
                    </button>
                </div>
            </div>
        <?php } else
        {
            ?>
            <input type="hidden" name="quantity[]" value="<?php echo $orderQuantityStartValue; ?>">
            <?php
        } //endregion
        ?>

        <?php //region add to cart button
        $addtoCartButton = (!$product->addToCartButton and $product->addToCartButton !== '')
            ? self::renderVmSubLayout('bs4-addtocartbtn', array ('orderable' => $product->orderable))
            : $product->addToCartButton;
        if (!empty($addtoCartButton))
        {
            ?>
            <div class="mb-3">
                <?php echo $addtoCartButton ?>
            </div>
            <?php
        } //endregion
        ?>

        <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
        <noscript><input type="hidden" name="task" value="add"/></noscript> <?php
    }
} ?>
