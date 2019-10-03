<?php
defined('_JEXEC') or die();

/**
 * @author Valérie Isaksen
 * @version $Id: payment_page.php 10139 2019-09-12 18:50:21Z Milbo $
 * @package VirtueMart
 * @subpackage vmpayment
 * @copyright Copyright (C) 2018 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
defined('_JEXEC') or die();
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true) . '/plugins/vmpayment/eway/assets/css/eway.css');




?>


<div id="eway-wallet-payment-page">
	<form method="POST" action="<?php echo $viewData['FormActionURL'] ?>" id="eway-payment-form">
		<input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $viewData['AccessCode'] ?>"/>
		<input type="hidden" name="EWAY_PAYMENTTYPE" value="<?php echo $viewData['payment_type'] ?>"/>
		<?php if (isset ($viewData['eway_cardcvn'])) { ?>
		<input type="hidden" name="EWAY_CARDCVN" value="<?php echo $viewData['eway_cardcvn'] ?>"/>
		<?php } ?>
		<div class="button">
			<input type="submit" name="btnSubmit" value=""/>
		</div>
	</form>
</div>


<script type="text/javascript" src="https://api.ewaypayments.com/JSONP/v3/js"></script>


<script type="text/javascript">
    // Add the submit handler
    var form = document.getElementById("eway-payment-form");
    if (form.addEventListener) {
        //Modern browsers
        form.addEventListener("submit", ewayAjax, false);
    } else if (form.attachEvent) {
        //Old IE
        form.attachEvent('onsubmit', ewayAjax);
    }

    function ewayAjax(e) {
        // call eWAY to process the request
        eWAY.process(
            document.getElementById("eway-payment-formm"),
            {
                autoRedirect: false,
                onComplete: function (data) {
                    // this is a callback to hook into when the requests completes
                    window.location.replace(data.RedirectUrl);
                },
                onError: function (e) {
                    // this is a callback you can hook into when an error occurs
                    alert('There was an error processing the request');
                },
                onTimeout: function (e) {
                    // this is a callback you can hook into when the request times out
                    alert('The request has timed out.');
                }
            }
        );
        // Stop the form from submitting
        e.preventDefault();
    }


</script>

<?php
vmJsApi::addJScript('vm.paymentFormAutoSubmit', '
  			jQuery(document).ready(function($){
   				jQuery("body").addClass("vmLoading");
  				var msg="'.vmText::sprintf("VMPAYMENT_EWAY_REDIRECT_MESSAGE", $viewData['payment_type']).'"
   				jQuery("body").append("<div class=\"vmLoadingDiv\"><div class=\"vmLoadingDivMsg\">"+msg+"</div></div>");
    			jQuery("#eway-payment-form").submit();
			})
		');
?>

