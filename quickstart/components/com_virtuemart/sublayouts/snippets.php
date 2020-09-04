<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument ();
$product = $viewData['product'];
$currency = $viewData['currency'];
$view = vRequest::getCmd('view');
$ratingModel = VmModel::getModel('Ratings');
$productrating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
if (is_object($productrating)) {
	$productratingcount = $productrating->ratingcount;
} else {
	$productratingcount = '0';
}

$canonicalUrl = JUri::getInstance()->toString(array('scheme', 'host', 'port')).JRoute::_($product->canonical);
$name = htmlspecialchars(strip_tags($product->product_name));
if (VmConfig::get('show_manufacturers', 1)){
	$brand = (!empty($product->mf_name)? $product->mf_name : '');
} else {
	$brand = '';
}
$sku =  (!empty($product->product_sku)? $product->product_sku : '');
$productID =  $sku;
$mpn =  (!empty($product->product_mpn)? $product->product_mpn : '');
$review =  (!empty($ratingModel->ids[0]->comment)? $ratingModel->ids[0]->comment : '');
$author =  (!empty($ratingModel->ids[0]->name)? $ratingModel->ids[0]->name : '');

//do something sensible and not fixed with valid price date
$DownDate = new DateTime($product->prices["product_price_publish_down"]);
$DownCalcDate = new DateTime();
if ($DownDate > $DownCalcDate) {
	$priceUntil = $DownDate->format( "Y-m-d" );
} else {
//  $DownCalcDate->modify('+20 years');
//  $priceUntil = $DownCalcDate->format( "Y-m-d" );
//  dont show an end to this price if there is not one
	$priceUntil = '';
}

//GJC look for parent & for child stock
$stockog = 'OutofStock';
$stockhandle = VmConfig::get ('stockhandle', 'none');
$finalstk = 0;
if ($stockhandle == 'none') {
	$stockog = 'InStock';
} elseif ($stockhandle == 'disableit_children' and $product->product_parent_id == 0) {
	$prodmodel = VmModel::getModel ('product');
	$children = $prodmodel->getProductChilds($product->virtuemart_product_id);
	$finalstk = '0';
	foreach($children as $child){
		$finalstk += $child->product_in_stock;
	}
} else {
	$finalstk = $product->product_in_stock;
}
if ($finalstk > 0) {
	$stockog = 'InStock';
}

//check for meta if empty move onto using product data
if (!empty($document->getMetaData('description'))) {
	$description = $document->getMetaData('description');
}
if (empty($description)){
	if (!empty($product->product_s_desc)){
		$description = $product->product_s_desc;
	} else {
		$description = $product->product_desc;
	}
}
$description = str_replace('"','\"',htmlspecialchars(strip_tags($description)));
?>

<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "<?php echo $name; ?>",
  "description":"<?php echo $description; ?>",
<?php if ($brand) { ?>
  "brand": {
    "@type": "Thing",
    "name": "<?php echo $brand; ?>"
  },
<?php
	}?>
<?php if ($productID) { ?>
  "productID":"<?php echo $productID; ?>",
<?php } ?>
<?php if ($sku) { ?>
  "sku": "<?php echo $sku; ?>",
<?php } ?>
<?php if ($mpn) { ?>
  "mpn": "<?php echo $mpn; ?>",
<?php } ?>
<?php if ( $product->images[0]->virtuemart_media_id > 0) {
		$i = 0;
		$numimages = count($product->images); ?>
  "image": [
<?php
		foreach($product->images as $image){
			echo '    "' . JURI::root().$image->file_url. '"';
			if (++$i != $numimages){
				echo ', ';
			}
		} ?>
  ],
<?php  } ?>
<?php if ($viewData['showRating'] && $productratingcount > 0) { ?>
  "aggregateRating":{
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $product->rating; ?>",
    "reviewCount": "<?php echo $productratingcount; ?>"
  },
<?php } ?>
<?php if ($review) { ?>
  "review": {
    "@type": "Review",
    "reviewBody": "<?php echo $review; ?>",
    "author": {
      "@type": "Person",
      "name": "<?php echo $author; ?>"
    }
  },
<?php } ?>
  "offers": {
    "@type": "Offer",
    "priceCurrency": "<?php echo $currency->_vendorCurrency_code_3; ?>",
    "availability": "<?php echo $stockog; ?>",
    "price": "<?php echo $product->prices['salesPrice']; ?>",
    "url": "<?php  echo $canonicalUrl; ?>",
<?php if ($priceUntil) { ?>
    "priceValidUntil": "<?php  echo $priceUntil; ?>",
<?php } ?>
    "itemCondition": "NewCondition"
  }
}
</script>