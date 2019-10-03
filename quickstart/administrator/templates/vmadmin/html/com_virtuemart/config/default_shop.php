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
 * @version $Id: default_shop.php 9821 2018-04-16 18:04:39Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>

<div class="well nr-well ">
     <h4><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_SHOP_SETTINGS'); ?></h4>
     <div class="well-desc"></div>

		<?php
			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_SHOP_OFFLINE','shop_is_offline',VmConfig::get('shop_is_offline',0), "", "", "yesno");
		?>
        <div class="control-group">
            <div class="control-label">
                <label><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_SHOP_OFFLINE_MSG'); ?></label>
            </div>
            <div class="controls">
                   <textarea rows="6" cols="50" name="offline_message"
				          style="text-align: left;"><?php echo VmConfig::get('offline_message', 'Our Shop is currently down for maintenance. Please check back again soon.'); ?></textarea>
            </div>
        </div>
		
		<?php
			echo VmHtml_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_USE_ONLY_AS_CATALOGUE','use_as_catalog',VmConfig::get('use_as_catalog',0),"", "", "yesno");
			echo VmHTML_override::row('genericlist','COM_VIRTUEMART_CFG_CURRENCY_MODULE',$this->currConverterList, 'currency_converter_module', 'size=1', 'value', 'text', VmConfig::get('currency_converter_module', 'convertECB.php'));
			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_ENABLE_CONTENT_PLUGIN','enable_content_plugin',VmConfig::get('enable_content_plugin',0), "", "", "yesno");

			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_SSL','useSSL',VmConfig::get('useSSL',0), "", "", "yesno");
			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_REGISTRATION_CAPTCHA','reg_captcha',VmConfig::get('reg_captcha',0), "", "", "yesno");
			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_VM_ERROR_HANDLING_ENABLE','handle_404',VmConfig::get('handle_404',1), "", "", "yesno");
		$host = JUri::getInstance()->getHost(); ?>
		<div class="control-group">
			<div class="control-label">
				 <label><?php echo vmText::_('COM_VM_EXTSUBSCR_HOST'); ?></label>
	        </div>
        	<div class="controls">
				<?php echo $host ?>
             </div>
       </div>
       <div class="control-group">
			<div class="control-label">
				<label id="jform_member_access_number-lbl" for="jform_member_access_number" class="hasPopover" data-content="<?php echo htmlentities(vmText::_('COM_VM_MEMBER_ACCESSNBR_TIP')); ?>"><?php echo vmText::_('COM_VM_MEMBER_ACCESSNBR')?></label>
	        </div>
        	<div class="controls">
				 <?php echo VmHTML_override::input('member_access_number',VmConfig::get('member_access_number','')); ?>
             </div>
       </div>
       <div class="control-group">
        <span class="hasTip" title="<?php echo htmlentities(vmText::sprintf($host,'COM_VM_MEMBER_AGREEMENT_TIP',VmConfig::$vmlangTag,vmVersion::$RELEASE))?>'"><?php echo vmText::_('COM_VM_MEMBER_AGREEMENT')?></span>
        </div>

         <?php
		//echo VmHTML_override::row('input','COM_VM_MEMBER_ACCESSNBR','member_access_number',VmConfig::get('member_access_number',''));
		?>

</div>


<div class="well nr-well ">
     <h4><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_SHOP_LANGUAGES'); ?></h4>
     <div class="well-desc"></div>


	<?php echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_ENABLE_ENGLISH','enableEnglish',VmConfig::get('enableEnglish',1),'','','yesno'); ?>
        <div class="control-group">
           <div class="control-label">
            <label id="jform_<?php echo vmText::_('activeShopLanguage'); ?>-lbl" for="jform_<?php echo vmText::_('activeShopLanguage'); ?>_alt" class="hasPopover" data-content="<?php echo vmText::_('COM_VM_CFG_SHOPLANG_TIP'); ?>"><?php echo vmText::sprintf('COM_VM_CFG_SHOPLANG',VmConfig::$jDefLang); ?></label>
          </div>
           <div class="controls">
          <?php echo $this->activeShopLanguage; ?>
          </div>
        </div>
         <div class="control-group">
           <div class="control-label">
            <label id="jform_<?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_MULTILANGUE'); ?>-lbl" for="jform_<?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_MULTILANGUE'); ?>_alt" class="hasPopover" data-content="<?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_MULTILANGUE_TIP'); ?>"><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_MULTILANGUE'); ?></label>
          </div>
           <div class="controls">
            <?php echo $this->activeLanguages; ?>
            <br />
            <span>
				<?php echo vmText::sprintf('COM_VIRTUEMART_MORE_LANGUAGES','https://virtuemart.net/community/translations'); ?>
				</span>
          </div>
        </div>
        
		<?php
		echo VmHTML_override::row('checkbox','COM_VM_CFG_NO_FALLBACK','prodOnlyWLang',VmConfig::get('prodOnlyWLang',0), '','','yesno');
		//echo VmHTML_override::row('checkbox','COM_VM_CFG_DUAL_FALLBACK','dualFallback',VmConfig::get('dualFallback',1));
		echo VmHTML_override::row('input','COM_VM_CFG_CUSTOM_FALLBACK','vm_lfbs',VmConfig::get('vm_lfbs',''));

		?>


</div>

<div class="well nr-well ">
     <h4><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_SHOP_ADVANCED'); ?></h4>
     <div class="well-desc"></div>

		<?php
			$optDebug = array(
				'none' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG_NONE'),
				'admin' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG_ADMIN'),
				'all' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG_ALL')
			);
			echo VmHTML_override::row('radiolist','COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG','debug_enable',VmConfig::get('debug_enable','none'), $optDebug);
		    echo VmHTML_override::row('checkbox','COM_VM_CFG_ENABLE_DEBUG_METHODS','debug_enable_methods',VmConfig::get('debug_enable_methods',0), '','','yesno');
			echo VmHTML_override::row('radiolist','COM_VIRTUEMART_CFG_DEV','vmdev',VmConfig::get('vmdev',0), $optDebug);
			echo VmHTML_override::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_DANGEROUS_TOOLS','dangeroustools',VmConfig::get('dangeroustools',0), '','','yesno');
			echo VmHTML_override::row('input','COM_VIRTUEMART_REV_PROXY_VAR','revproxvar',VmConfig::get('revproxvar',''));
			$optMultiX = array(
				'none' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_MULTIX_NONE'),
				'admin' => vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_MULTIX_ADMIN')

				// 				'all'	=> vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG_ALL')
			);
			echo VmHTML_override::row('radiolist','COM_VIRTUEMART_ADMIN_CFG_ENABLE_MULTIX','multix',VmConfig::get('multix','none'), $optMultiX);
		$optMultiX = array(
			'0' => vmText::_('COM_VIRTUEMART_CFG_MULTIX_CART_NONE'),
			'byproduct' => vmText::_('COM_VIRTUEMART_CFG_MULTIX_CART_BYPRODUCT'),
			'byvendor' => vmText::_('COM_VIRTUEMART_CFG_MULTIX_CART_BYVENDOR'),
			'byselection' => vmText::_('COM_VIRTUEMART_CFG_MULTIX_CART_BYSELECTION')
			// 				'all'	=> vmText::_('COM_VIRTUEMART_ADMIN_CFG_ENABLE_DEBUG_ALL')
		);
		echo VmHTML_override::row('radiolist','COM_VIRTUEMART_CFG_MULTIX_CART','multixcart',VmConfig::get('multixcart',0), $optMultiX);

		?>
</div>