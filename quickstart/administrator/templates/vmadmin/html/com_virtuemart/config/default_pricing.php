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
 * @version $Id: default_pricing.php 9821 2018-04-16 18:04:39Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$js = 'Virtuemart.showprices;';
vmJsApi::addJScript('show_prices',$js,true);
?>
<div class="">
<div class="span6">
			
      <div class="well nr-well ">
         <h4><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_PRICE_CONFIGURATION'); ?></h4>
         <div class="well-desc"></div>
					<?php
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_PRICE_SHOW_TAX','show_tax',VmConfig::get('show_tax',1),'','','yesno');
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_PRICE_ASKPRICE','askprice',VmConfig::get('askprice',1),'','','yesno');
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_PRICE_RAPPENRUNDUNG','rappenrundung',VmConfig::get('rappenrundung',0),'','','yesno');
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_PRICE_ROUNDINDIG','roundindig',VmConfig::get('roundindig',1),'','','yesno');
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_PRICE_CVARSWT','cVarswT',VmConfig::get('cVarswT',1),'','','yesno');

					echo VmHTML_override::row('genericlist','COM_VIRTUEMART_ADMIN_CFG_PRICE_ORDERBY',$this->orderDirs, 'price_orderby', '', 'value', 'text', VmConfig::get('price_orderby','DESC'));
					?>
         </div>
   </div>
   <div class="span6">
			
      <div class="well nr-well ">
         <h4><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_PRICES'); ?></h4>
         <div class="well-desc"></div>
				
					<?php
					echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_SHOW_PRICES','show_prices',VmConfig::get('show_prices',1),1,0,'id="show_prices"');
					?>

				<?php
				$params = $this->config->_params;
				$showPricesLine = false;
				include("default_priceconfig.php");
                ?>
            </div>
	</div>
</div>