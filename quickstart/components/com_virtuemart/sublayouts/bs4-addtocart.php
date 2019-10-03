<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author     Max Milbers, Valerie Isaksen
 * @todo       handle child products
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version    $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$product               = $viewData['product'];
$customFieldsPositions = (isset($viewData['position']))
    ? $viewData['position']
    : 'addtocart';

if (!is_array($customFieldsPositions)) $customFieldsPositions = array ($customFieldsPositions);
?>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_virtuemart', FALSE); ?>">

    <?php
    // region custom fields
    if (!empty($customFieldsPositions))
    {
        ?>
        <div class="vm-store-product-custom-fields-container">
            <?php
            foreach ($customFieldsPositions as $customFieldsPosition)
            {
                echo shopFunctionsF::renderVmSubLayout(
                    'bs4-customfields',
                    array (
                        'product' => $product,
                        'position' => $customFieldsPosition,
                    )
                );
            }
            ?>
        </div>
    <?php }
    //endregion ?>

    <?php
    //region add to car bar
    if (!VmConfig::get('use_as_catalog', 0))
    {
        echo shopFunctionsF::renderVmSubLayout('bs4-addtocartbar', array ('product' => $product));
    }
    //endregion
    ?>

    <input type="hidden" name="option" value="com_virtuemart"/>
    <input type="hidden" name="view" value="cart"/>
    <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
    <input type="hidden" name="pname" value="<?php echo $product->product_name ?>"/>
    <input type="hidden" name="pid" value="<?php echo $product->virtuemart_product_id ?>"/>
    <?php
    $itemId = vRequest::getInt('Itemid', FALSE);
    if ($itemId)
    {
        ?>
        <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>"/>
        <?php
    } ?>
</form>