<?php
/**
 * sublayout product rating small
 *
 * @package    VirtueMart
 * @author     Max Milbers
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version    $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');

$product           = $viewData['product'];
$showProductRating = $viewData['show_rating'];

if ($showProductRating && !empty($product->rating))
{
    $ratingWidthInPixel = ($product->rating * 12) . "px;";
    ?>
    <div class="vm-store-product-rating-stars d-inline-block">
        <div style="width:<?php echo $ratingWidthInPixel; ?>"></div>
    </div>
    <?php
}