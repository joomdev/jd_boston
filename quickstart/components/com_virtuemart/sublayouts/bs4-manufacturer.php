<?php
// Joomla Security Check - no direct access to this file 
// Prevents File Path Exposure
defined('_JEXEC') or die('Restricted access');

// output the passed array / object content
$manufacturer = $viewData['bs4-manufacturer'];

// link to the manufacturer details
$manufacturerURL = JRoute::_(
    'index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id,
    FALSE
);

// link to the category page which shows the products of this specific manufacturer
$manufacturerIncludedProductsURL = JRoute::_(
    'index.php?option=com_virtuemart&view=category&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id,
    FALSE
);
?>
<div class="card center">
    <div>
        <?php echo $manufacturer->images[0]->displayMediaThumb('class="img-fluid vm-category-thumbnail"', FALSE); ?>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?php echo $manufacturer->mf_name; ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <a href="<?php echo $manufacturerIncludedProductsURL ?>"
               title="<?php echo vmText::_($manufacturer->mf_name) ?>"
               class="btn btn-link">
                <?php echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_FROM_MF', $manufacturer->mf_name); ?>
            </a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo $manufacturerURL ?>" title="<?php echo vmText::_($manufacturer->mf_name) ?>"
               class="btn btn-link">
                <?php echo vmText::_('COM_VIRTUEMART_MANUFACTURER_DETAILS'); ?>
            </a>
        </li>
    </ul>
</div>