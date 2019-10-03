<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author Valérie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit.php 10002 2018-12-18 10:06:22Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$urlTemplateHtml = JURI::root(TRUE) .'/administrator/templates/vmadmin/html';
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/styles.css');
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/style2.css');
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/menu.css');
$document->addScript($urlTemplateHtml.'/com_virtuemart/assets/script.js');
$document->addScript($urlTemplateHtml.'/com_virtuemart/assets/sidemenu.js');


vmJsApi::addJScript( '/administrator/components/com_virtuemart/assets/js/dynotable.js', false, false );
vmJsApi::addJScript( '/administrator/components/com_virtuemart/assets/js/products.js', false, false );


$app = JFactory::getApplication();
$templatename = $app->getTemplate();

require_once(VMPATH_ROOT .'/administrator/templates/vmadmin/html/com_virtuemart/assets/helper.php');
require_once(VMPATH_ROOT .'/administrator/templates/vmadmin/html/com_virtuemart/assets/adminui.php');

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tooltip');


vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/jquery.coookie.js');
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/vm2admin.js');
AdminUIHelper_override::startAdminArea($this);

vmJsApi::JvalideForm();
$this->editor = JFactory::getEditor();

?>
<div class="nr-app nr-app-config">
	<div class="nr-row">


		<div class="nr-main-container"><!-- Main bar started -->
			<div class="nr-main-header">
				<h2><?php echo $this->product->product_name; ?></h2>
			</div>
			<div class="nr-main-content">
				<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
					<div class="form-horizontal">
						<ul class="nav nav-tabs" id="tabTabs">
							<li class="active"><a href="#information" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_INFO_LBL') ?></a></li>
							<li class=""><a href="#description" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_DESCRIPTION') ?></a></li>
							<li class=""><a href="#product-status" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_STATUS_LBL') ?></a></li>
							<li class=""><a href="#dimensions" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_DIM_WEIGHT_LBL') ?></a></li>
							<li class=""><a href="#images" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_IMAGES_LBL') ?></a></li>
							<?php if(!empty($this->product_childs)){ ?>
							<li class=""><a href="#childs" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_CHILD_LIST') ?></a></li>
							<?php }?>
							<li class=""><a href="#custom" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_CUSTOM_TAB') ?></a></li>
						</ul>
						<div class="maincontentdiv"  style="padding:1.5%">
							<div class="tab-content" id="tabContent">
								<div id="information" class="tab-pane active">
									<?php include("product_edit_information.php")  ?>
								</div>
								<div id="description" class="tab-pane ">
									<?php include("product_edit_description.php")  ?>
								</div>
								<div id="product-status" class="tab-pane ">
									<?php include("product_edit_status.php")  ?>
								</div>
								<div id="dimensions" class="tab-pane ">
									<?php include("product_edit_dimensions.php")  ?>
								</div>
								<div id="images" class="tab-pane ">
									<?php include("product_edit_images.php")  ?>
								</div>
								<?php if(!empty($this->product_childs)){ ?>
								<div id="childs" class="tab-pane ">
									<?php include("product_edit_childs.php")  ?>
								</div>
								<?php }?>
								<div id="custom" class="tab-pane ">
									<?php include("product_edit_custom.php")  ?>
								</div>
								<div style="clear:both !important; "></div>
							</div>
						</div>
						<input type="hidden" name="task" value="" /> <input
								type="hidden" name="option" value="com_virtuemart" />
						<input type="hidden" name="view" value="product" />
						<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>" />
						<?php
						echo JHtml::_ ( 'form.token' );
						?>
				</form>
			</div>
		</div><!-- Main bar Ended -->
	</div>
</div>


<?php // Loading Templates in Tabs


$l = 'index.php?option=com_virtuemart&view=product&task=getData&format=json&virtuemart_product_id='.$this->product->virtuemart_product_id;
if(JFactory::getApplication()->isAdmin()){
	$jsonLink = JURI::root(false).'administrator/'.$l;
} else {
	$jsonLink = JRoute::_($l);
}

$j = 'if (typeof Virtuemart === "undefined")
	var Virtuemart = {};
	Virtuemart.nextCustom ="'.count($this->product->customfields).'";
	Virtuemart.jsonLink ="'.$jsonLink.'";
	Virtuemart.virtuemart_product_id ="'.$this->product->virtuemart_product_id.'";
	Virtuemart.urlDomain = "'.JURI::root ().'";
	Virtuemart.msgsent = "'.addslashes (vmText::_ ('COM_VIRTUEMART_PRODUCT_NOTIFY_MESSAGE_SENT')).'";
	Virtuemart.enterSubj = "'.vmText::_ ('COM_VIRTUEMART_PRODUCT_EMAIL_ENTER_SUBJECT').'";
	Virtuemart.enterBody = "'.vmText::_ ('COM_VIRTUEMART_PRODUCT_EMAIL_ENTER_BODY').'";
	Virtuemart.customfields;
	Virtuemart.prdcustomer;
	Virtuemart.edit_status;
	Virtuemart.imagePath = "'.JURI::root(true).$this->imagePath.'";
	Virtuemart.token = "'.JSession::getFormToken().'";
	';
vmJsApi::addJScript('onReadyProduct',$j);


//$document->addScriptDeclaration( 'jQuery(window).load(function(){ jQuery.ajaxSetup({ cache: false }); })'); ?>
