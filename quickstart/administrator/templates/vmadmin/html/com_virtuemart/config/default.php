<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage Config
 * @author RickG
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 9821 2018-04-16 18:04:39Z Milbo $
 */

$document = JFactory::getDocument();
$urlTemplateHtml = JURI::root(TRUE) .'/administrator/templates/vmadmin/html';
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/styles.css');
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/style2.css');
$document->addStyleSheet($urlTemplateHtml.'/com_virtuemart/assets/menu.css');
$document->addScript($urlTemplateHtml.'/com_virtuemart/assets/script.js');
$document->addScript($urlTemplateHtml.'/com_virtuemart/assets/sidemenu.js');


$app = JFactory::getApplication();
$templatename = $app->getTemplate();

require_once(VMPATH_ROOT .'/administrator/templates/vmadmin/html/com_virtuemart/assets/helper.php');
require_once(VMPATH_ROOT .'/administrator/templates/vmadmin/html/com_virtuemart/assets/adminui.php');




// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/jquery.coookie.js');
vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/vm2admin.js');
AdminUIHelper_override::startAdminArea($this);
?>

<div class="nr-app nr-app-config">
    <div class="nr-row" style="margin-left:0px;">

     
        <div class="nr-main-container"><!-- Main bar started -->
	        <div class="nr-main-header">
    	        <h2>Configuration</h2>
        	    <p>Component's Advanced Settings.</p>
            </div>
		   <div class="nr-main-content">
               <form action="index.php" method="post" name="adminForm" id="adminForm">
				   <?php // Loading Templates in Tabs
				   AdminUIHelper_override::buildTabs ( $this,  array (
				   'shop' 			=> 	'COM_VIRTUEMART_ADMIN_CFG_SHOPTAB',
				   'email' 		=> 	'COM_VIRTUEMART_ADMIN_CFG_ORDERTAB',
				   'shopfront' 	=> 	'COM_VIRTUEMART_ADMIN_CFG_SHOPFRONTTAB',
				   'templates' 	=> 	'COM_VIRTUEMART_ADMIN_CFG_TEMPLATESTAB',
				   'pricing' 		=> 	'COM_VIRTUEMART_ADMIN_CFG_PRICINGTAB',
				   'checkout' 		=> 	'COM_VIRTUEMART_ADMIN_CFG_CHECKOUTTAB',
				   'product_order'	=> 	'COM_VIRTUEMART_ADMIN_CFG_PRODUCTORDERTAB',
				   'feeds'			=> 	'COM_VIRTUEMART_ADMIN_CFG_FEEDS',
				   'sef' 			=> 	'COM_VIRTUEMART_ADMIN_CFG_SEF'
				   ));
				   ?>

                <!-- Hidden Fields --> <input type="hidden" name="task" value="" /> <input
                    type="hidden" name="option" value="com_virtuemart" /> <input
                    type="hidden" name="view" value="config" />
                <?php
                echo JHtml::_ ( 'form.token' );
                ?>
                </form>
             </div>
          </div><!-- Main bar Ended -->
      </div>
  </div>
                <?php
//AdminUIHelper::endAdminArea ();

?>