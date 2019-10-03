<?php
/**
*
* Set the descriptions for a product
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD, Valérie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_description.php 10002 2018-12-18 10:06:22Z alatak $
*/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_S_DESC').' '.  $this->origLang; ?></h4>
	<div class="well-desc"></div>
	<?php echo VmHTML_override::row('textarea', vmText::_('COM_VIRTUEMART_PRODUCT_FORM_S_DESC').' '.  $this->origLang, 'product_s_desc',$this->product->product_s_desc,'class="nr-textarea"', 80); ?>
</div>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_DESCRIPTION').' '.  $this->origLang; ?></h4>
	<div class="well-desc"></div>

	<?php echo $this->editor->display('product_desc',  $this->product->product_desc, '90%;', '450', '55', '10', array('pagebreak', 'readmore') ) ; ?>

</div>

<div class="well nr-well ">
	<h4><?php echo vmText::_('COM_VIRTUEMART_METAINFO').' '.  $this->origLang; ?></h4>
	<div class="well-desc"></div>

	<?php echo VmHtml_override::renderMetaEdit($this->product); ?>

</div>



