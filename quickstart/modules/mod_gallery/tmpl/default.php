<?php
/**
 * @package	JD Gallery
 * @subpackage  mod_slider
 * @version	1.0
 * @author	Joomdev.com
 * @copyright	Copyright (C) 2008 - 2018 Joomdev.com. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$photo_gallery = json_decode($params->get('photo_gallery'), true); // image, title & description

$photo_in_row_lx = $params->get('photo_in_row_lx');
$photo_in_row_md = $params->get('photo_in_row_md');
$photo_in_row_sm = $params->get('photo_in_row_sm');

$items = [];
foreach ($photo_gallery as $key => $photo_gallery_items) {
   foreach ($photo_gallery_items as $index => $value) {
      if (!isset($items[$index])) {
         $items[$index] = [];
         $items[$index][$key] = $value;
      } else {
         $items[$index][$key] = $value;
      }
   }
}
$desktop_items = $items;

$document = JFactory::getDocument();
JHtml::_('jquery.framework');
$document->addScript('//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js');
$document->addScript('//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js');
$document->addStylesheet('//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');
?>
<!-- The Photo Section -->
<div class="row gallery-section">
   <?php
   foreach ($desktop_items as $desktop_item) {
      $size_lx = 12 / $photo_in_row_lx;
      $size_md = 12 / $photo_in_row_md;
      $size_sm = 12 / $photo_in_row_sm;
      ?>
      <div class="col-lg-<?php echo $size_lx; ?> col-md-<?php echo $size_md; ?> col-sm-<?php echo $size_sm; ?> p-2">
         <a data-fancybox="gallery-<?php echo $module->id; ?>" href="<?php echo JURI::root() . $desktop_item["photo_gallery_image"]; ?>">
            <img class="img-fluid" src="<?php echo JURI::root() . $desktop_item["photo_gallery_image"]; ?>">
            <div class="overlay">
               <div class="overlay-border">
                  <i class="fas fa-search-plus"></i>
               </div>
            </div>
         </a>
      </div>
   <?php } ?>
</div>

