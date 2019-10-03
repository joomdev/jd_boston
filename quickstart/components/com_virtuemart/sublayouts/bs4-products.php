<?php
/**
 * sublayout products
 *
 * @package    VirtueMart
 * @author     Max Milbers
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version    $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

// Joomla Security Check - no direct access to this file
// Prevents File Path Exposure
defined('_JEXEC') or die('Restricted access');

// output the passed array / object content
$product           = $viewData['bs4-products'];
$showProductRating = $viewData['showRating'];
$productCurrency   = $viewData['currency'];

// Create Product Link
$menuItemID = shopFunctionsF::getLastVisitedItemId();
if (!empty($menuItemID))
{
    $product->link .= '&Itemid=' . $menuItemID;
}
?>

<div class="card text-center">
    <div class="">
        <?php echo $product->images[0]->displayMediaThumb('class="img-fluid vm-category-thumbnail"', FALSE); ?>
    </div>
    <div class="card-body">

        <span class="card-title h5 d-block mb-1"><?php echo $product->product_name; ?></span>

        <?php // Product Rating Stars
        if ($showProductRating)
        {
            ?>
            <div class="vm-store-product-rating mb-3">
                <?php
                echo shopFunctionsF::renderVmSubLayout(
                    'bs4-rating-small',
                    array (
                        'show_rating' => $showProductRating,
                        'product' => $product,
                    )
                );
                ?>
            </div>
        <?php } ?>

        <?php // Product Short Description
        if (!empty($product->product_s_desc))
        {
            ?>
            <div class="vm-store-product-short-desc mb-3">
                <?php echo shopFunctionsF::limitStringByWord($product->product_s_desc, 60, ' ...') ?>
            </div>
        <?php } ?>

        <?php // Product Price
        // echo shopFunctionsF::renderVmSubLayout(
        //     'prices',
        //     array (
        //         'product' => $product,
        //         'currency' => $productCurrency,
        //     )
        // );
        ?>

        <?php // Product Add To Cart Area Including Fields
        echo shopFunctionsF::renderVmSubLayout(
            'bs4-addtocart',
            array (
                'product' => $product,
                'position' => array ('ontop', 'addtocart'),
            )
        );
        ?>

        <a href="<?php echo $product->link ?>" title="<?php echo vmText::_($product->product_name) ?>"
           class="btn btn-primary btn-block">
            <?php echo vmText::_('JSHOW'); ?>
        </a>

        <?php
        if (VmConfig::get('display_stock', 1))
        { ?>
            <div>
            <span class="vmicon vm2-<?php echo $product->stock->stock_level ?>"
                  title="<?php echo $product->stock->stock_tip ?>"></span>
            </div>
        <?php }

        //echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$product));
        ?>
    </div>
</div>