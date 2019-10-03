<?php
// Joomla Security Check - no direct access to this file 
// Prevents File Path Exposure
defined('_JEXEC') or die('Restricted access');

// output the passed array / object content
$vendor = $viewData['bs4-vendor'];

// link to the vendor details
$vendorDetailsLink = JRoute::_(
    'index.php?option=com_virtuemart&view=vendor&virtuemart_vendor_id=' . $vendor->virtuemart_vendor_id,
    FALSE
);

// link to the category page which shows the products of this specific vendor
$vendorProductsLink = JRoute::_(
    'index.php?option=com_virtuemart&view=category&virtuemart_vendor_id=' . $vendor->virtuemart_vendor_id,
    FALSE
);
?>
<div class="card center">
    <div>
        <?php echo $vendor->images[0]->displayMediaThumb('class="img-fluid vm-category-thumbnail"', FALSE); ?>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?php echo $vendor->vendor_store_name; ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <a href="<?php echo $vendorProductsLink ?>"
               title="<?php echo vmText::_($vendor->vendor_store_name) ?>"
               class="btn btn-link">
                <?php echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_FROM_MF', $vendor->vendor_store_name); ?>
            </a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo $vendorDetailsLink ?>" title="<?php echo vmText::_($vendor->vendor_store_name) ?>"
               class="btn btn-link">
                <?php echo vmText::_('COM_VIRTUEMART_VENDOR_DETAILS'); ?>
            </a>
        </li>
    </ul>
</div>