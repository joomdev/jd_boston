<?php
defined ('_JEXEC') or die();
/**
 * @author Valérie Isaksen
 * @version $Id: display_payment.php 9003 2015-09-29 15:58:36Z Milbo $
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-Copyright (C) 2004 - 2017 Virtuemart Team. All rights reserved.   - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
if (!empty($viewData['payment_logo_link'] )) {

	vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack');
	vmJsApi::css('jquery.fancybox-1.3.4');
	$box = "jQuery.fancybox({
			href: '" .$viewData['payment_logo_link']  . "',
			type: 'iframe',
			height: '550'
		});";

	$document = JFactory::getDocument();
	$document->addScriptDeclaration("
//<![CDATA[
	jQuery(document).ready(function($) {
		$('a.payment-logo-link').click( function(){
			".$box."
			return false ;
		});

	});
//]]>
");
}
?>

<input type="radio" name="virtuemart_paymentmethod_id"
       id="payment_id_<?php echo $viewData['plugin']->virtuemart_paymentmethod_id; ?>"
       value="<?php echo $viewData['plugin']->virtuemart_paymentmethod_id; ?>" <?php echo $viewData ['checked']; ?>>
<label for="payment_id_<?php echo $viewData['plugin']->virtuemart_paymentmethod_id; ?>">
    <span class="vmpayment">
        <?php if (!empty($viewData['payment_logo_link'] )) { ?>
	        <a class="payment-logo-link" title="<?php echo vmText::_('VMPAYMENT_SOFORT_READMORE')?>" href="<?php echo $viewData ["payment_logo_link"]; ?>" >
        <?php } ?>
        <?php if (!empty($viewData['payment_logo'] )) { ?>
	        <span class="vmCartPaymentLogo"><?php echo $viewData ['payment_logo']; ?> </span>
        <?php } ?>
	    <?php if (!empty($viewData['payment_logo_link'] )) { ?>
		    </a>
	    <?php } ?>
	    <span class="vmpayment_name"><?php echo $viewData['plugin']->payment_name; ?></span>
	    <?php if (!empty($viewData['plugin']->payment_desc )) { ?>
		    <span class="vmpayment_description"><?php echo $viewData['plugin']->payment_desc; ?></span>
	    <?php } ?>
	    <?php if (!empty($viewData['payment_cost']  )) { ?>
		    <span class="vmpayment_cost"><?php echo vmText::_ ('COM_VIRTUEMART_PLUGIN_COST_DISPLAY') .  $viewData['payment_cost']  ?></span>
	    <?php } ?>
    </span>
</label>