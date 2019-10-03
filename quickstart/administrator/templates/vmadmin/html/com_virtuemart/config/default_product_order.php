<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Config
 * @author RickG
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_product_order.php 7388 2013-11-18 13:32:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>

<div class="">
<div class="span6">
  <div class="well nr-well ">
 
	 <h4><?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_DEFAULT_FIELD_LBL'); ?></h4>
     <div class="well-desc"></div>
     
         <div class="control-group">
              <div class="control-label">
                <label id="jform_<?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_DEFAULT_FIELD_LBL'); ?>-lbl" for="jform_<?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_DEFAULT_FIELD_LBL'); ?>_alt" class="hasPopover" data-content="<?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_DEFAULT_FIELD_LBL_TIP'); ?>"> <?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_DEFAULT_FIELD_LBL'); ?></label>
              </div>
               <div class="controls">
             		<?php echo JHtml::_('Select.genericlist', $this->orderByFieldsProduct->select, 'browse_orderby_field', 'size=1', 'value', 'text', VmConfig::get('browse_orderby_field', 'product_name'), 'product_name');

							 ?>
              </div>
              
               <div class="control-group" style="margin-top:10px;">
                   <div class="control-label">
                   </div>
	               <div class="controls">
                    <?php 
					echo JHtml::_('select.genericlist', $this->orderDirs, 'prd_brws_orderby_dir', 'size=10', 'value', 'text', VmConfig::get('prd_brws_orderby_dir', 'ASC') );
					?>
				
                   </div>
               </div>
            </div>
     </div>
     <div class="well nr-well ">
         <h4><?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_FIELDS_LBL'); ?></h4>
         <div class="well-desc"><?php echo vmText::_('COM_VIRTUEMART_BROWSE_ORDERBY_FIELDS_LBL_TIP'); ?></div>
         
         <fieldset class="checkbox 2colums">
         <?php
			echo $this->orderByFieldsProduct->checkbox; 
			?>
         </fieldset>
     </div>
</div>
<div class="span6">
   <div class="well nr-well ">
	 <h4><?php echo vmText::_('COM_VIRTUEMART_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL'); ?></h4>
     <div class="well-desc"></div>           
           
            
             <div class="control-group">
              <div class="control-label">
                <label id="jform_<?php echo vmText::_('COM_VIRTUEMART_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL'); ?>-lbl" for="jform_<?php echo vmText::_('COM_VIRTUEMART_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL'); ?>_alt" class="hasPopover" data-content="<?php echo vmText::_('COM_VIRTUEMART_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL_TIP'); ?>"> <?php echo vmText::_('COM_VIRTUEMART_BROWSE_CAT_ORDERBY_DEFAULT_FIELD_LBL'); ?></label>
              </div>
               <div class="controls">
             		<?php //Fallback, if someone used an old ordering: "ordering"
							$ordering = VmConfig::get('browse_cat_orderby_field', 'c.ordering,category_name');
							if(!in_array($ordering,VirtueMartModelCategory::$_validOrderingFields)){
								$ordering = 'c.ordering,category_name';
							}
							echo JHtml::_('Select.genericlist', $this->orderByFieldsCat, 'browse_cat_orderby_field', 'size=1', 'value', 'text', $ordering, 'category_name');
							 ?>
              </div>
               <div class="control-group" style="margin-top:10px;">
                   <div class="control-label">
                   </div>
	               <div class="controls">
                    <?php 
					echo JHtml::_('select.genericlist', $this->orderDirs, 'cat_brws_orderby_dir', 'size=10', 'value', 'text', VmConfig::get('cat_brws_orderby_dir', 'ASC') );
					?>
				
                   </div>
               </div>
            </div>
        </div>
        
         <div class="well nr-well ">
             <h4><?php echo vmText::_('COM_VIRTUEMART_BROWSE_SEARCH_FIELDS_LBL'); ?></h4>
             <div class="well-desc"><?php echo vmText::_('COM_VIRTUEMART_BROWSE_SEARCH_FIELDS_LBL_TIP'); ?></div>
             
             <fieldset class="checkbox 2colums">
             <?php
                echo $this->searchFields->checkbox; 
                ?>
             </fieldset>
         </div>
        
    </div>
 </div>
				
		