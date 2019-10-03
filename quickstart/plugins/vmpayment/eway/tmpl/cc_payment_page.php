<?php
defined('_JEXEC') or die();
/**
 * @author Valérie Isaksen
 * @version $Id: cc_payment_page.php 10139 2019-09-12 18:50:21Z Milbo $
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
vmJsApi::addJScript('/plugins/vmpayment/eway/assets/js/jquery.payform.min.js');
$maskedCard = $viewData['maskedCard'];
if ($viewData['action'] != 'pay') {
	if (VmConfig::get('usefancy', 1)) {
		$onclick = 'parent.jQuery.fancybox.close();';
	} else {
		$onclick = 'parent.jQuery.facebox.close();';
	}
}

$readonly = '';
if ($viewData['action'] == 'delete') {
	$readonly = " readonly";
}

?>
	<div id="eway-page">

		<h1><?php echo $viewData['pageTitle'] ?></h1>

		<?php if ($viewData['sandbox']) {
			echo '<p><span style="color:red;font-weight:bold">Your payment is set in sandbox mode. No real money is transferred and this is not suitable for live sites.</span></p>';
			echo '<p><span style="color:red;font-weight:bold"><a href="https://go.eway.io/s/article/Test-Credit-Card-Numbers" target="_blank">Test Credit Card Numbers</a></span></p>';
		}
		?>

		<?php if ($viewData['action'] != 'delete') { ?>

		<form method="POST" action="<?php echo $viewData['FormActionURL'] ?>" id="eway-payment-form" autocomplete="on"
			  class="eway-payment-form">
			<input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $viewData['AccessCode'] ?>"/>
			<?php } else {
			?>
			<div id="eway-payment-<?php echo $viewData['action'] ?>"
				 class="eway-payment-<?php echo $viewData['action'] ?>">
				<?php
				} ?>

				<div id="payment payment-<?php echo $viewData['action'] ?>">
					<div class="transactioncustomer">
						<div class="eway-form-group">
							<label for="EWAY_CARDNAME" class="eway-control-label">
								<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CARD_HOLDER') ?></label>
							<input type="text" name="EWAY_CARDNAME" id="EWAY_CARDNAME"
								   class="eway-form-control"
								   placeholder="<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CARD_HOLDER_PLACE') ?>"
								   value="<?php echo $maskedCard->Name ?>"
								<?php echo $readonly ?>
							/>
						</div>
						<div class="eway-form-group">
							<label for="EWAY_CARDNUMBER" class="eway-control-label">
								<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CARD_NUMBER') ?></label>
							<input type="tel" name="EWAY_CARDNUMBER" id="EWAY_CARDNUMBER"
								   class="eway-form-control" autocomplete="cc-number"
								   placeholder="4444333322221111"
								   value="<?php echo $maskedCard->Number ?>"
								<?php echo $readonly ?>
							/>
						</div>
						<div class="eway-form-group eway-cardexpiry-group">
							<label for="EWAY_CARDEXPIRYMONTH" class="eway-control-label">
								<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_EXPIRY_DATE') ?></label>
							<div class="eway-cardexpiry-select">
								<?php if ($viewData['action'] != 'delete') { ?>
									<select id="EWAY_CARDEXPIRYMONTH" name="EWAY_CARDEXPIRYMONTH"
											class="eway-cardexpirymonth">
										<?php
										$expiry_month = date('m');
										for ($i = 1; $i <= 12; $i++) {
											$month = sprintf('%02d', $i);
											$selected = '';
											if ($maskedCard->ExpiryMonth) {
												if ($maskedCard->ExpiryMonth == $i) {
													$selected = " selected='selected'";
												}
											} elseif ($expiry_month == $i) {
												$selected = " selected='selected'";
											}
											?>
											<option value="<?php echo $month ?>" <?php echo $selected ?>><?php echo $month ?></option>
										<?php } ?>
									</select>
									<select id="EWAY_CARDEXPIRYYEAR" name="EWAY_CARDEXPIRYYEAR"
											class="eway-cardexpiryyear">
										<?php
										$i = date("y");
										$j = $i + 11;
										for ($i; $i <= $j; $i++) {
											$selected = '';
											if ($maskedCard->ExpiryYear) {
												if ($maskedCard->ExpiryYear == $i) {
													$selected = " selected='selected'";
												}
											} elseif ($expiry_month == $i) {
												$selected = " selected='selected'";
											}
											?>
											<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?></option>
										<?php } ?>
									</select>
								<?php } else { ?>
									<input type="text" name="EWAY_CARDEXPIRYMONTH" id="EWAY_CARDEXPIRYMONTH"
										   class="eway-delete-cardexpirymonth"
										   value="<?php echo $maskedCard->ExpiryMonth ?>"
										   readonly
									/> /
									<input type="text" name="EWAY_CARDEXPIRYYEAR" id="EWAY_CARDEXPIRYYEAR"
										   class="eway-delete-cardexpiryyear"
										   value="<?php echo $maskedCard->ExpiryYear ?>"
										   readonly
									/>

								<?php } ?>
							</div>
						</div>
						<?php if ($viewData['action'] != 'delete') { ?>
							<div class="eway-form-group eway-cardcvn-group">
								<label for="EWAY_CARDCVN" class="eway-control-label">
									<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CVN') ?></label>
								<input type="text" name="EWAY_CARDCVN" id="EWAY_CARDCVN" autocomplete="off"
									   class="eway-form-control cc-cvn"
									   placeholder="123"
									   value="<?php echo $maskedCard->CVN ?>" maxlength="4"
								/> <!-- This field is optional but highly recommended -->
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<div class="control-buttons">
						<?php
						$class = "";
						if ($viewData['action'] != 'pay') {
							$class = "floatleft width50";
						} ?>
						<div class="<?php echo $class ?> ">
							<input type="submit" class="vm-button-correct" id="ewaySubmit" name="btnSubmit"
								   value="<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_' . $viewData['action']) ?>"
								<?php if ($viewData['action'] == 'delete') { ?>
									data-eway="<?php echo $viewData['maskedCardCrypted'] ?>"
								<?php } ?>
							/>
						</div>
						<?php if ($viewData['action'] != 'pay') { ?>
							<div class="floatleft width50 ">
								<input type="submit" class="vm-buttone eway-cancel" onclick="<?php echo $onclick ?>"
									   value="<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CANCEL') ?>"/>
							</div>
						<?php } ?>

						<?php if ($viewData['action'] == 'delete') { ?>
							<input type="hidden" data-eway="<?php echo $viewData['maskedCardCrypted'] ?>"/>
						<?php } ?>
					</div>

				</div>
				<?php if ($viewData['action'] != 'delete') { ?>
		</form>
		<?php } else {
		?>
	</div>
	<?php
} ?>


<?php if ($viewData['action'] != 'pay') { ?>
	<div id="eway-result">
		<p><?php echo vmText::_('VMPAYMENT_EWAY_' . $viewData['action'] . '_CREDIT_CARD_SUCCESSFULL'); ?></p>
		<div class="">
			<button id="eway-result-close" class="vm-button-correct"
					type="button"><?php echo vmText::_('COM_VIRTUEMART_CLOSE'); ?></button>
		</div>
	</div>
<?php } ?>

	</div>


	<script type="text/javascript" src="https://api.ewaypayments.com/JSONP/v3/js"></script>
<?php if ($viewData['action'] != 'delete') { ?>

	<script type="text/javascript">

      jQuery(document).ready(function ($) {
		  <?php if ($viewData['action'] != 'pay') { ?>
        $('#eway-result').hide()
		  <?php } ?>

        $('#EWAY_CARDNUMBER').payform('formatCardNumber')
        $('#EWAY_CARDCVN').payform('formatCardCVC')
        $('#EWAY_CARDCVN').payform('formatNumeric')

        $.fn.toggleInputError = function (erred) {
          this.parent('.eway-form-group').toggleClass('eway-error', erred)
          return this
        }

        // Add the submit handler
        var form = document.getElementById('eway-payment-form')
        if (form.addEventListener) {
          //Modern browsers
          form.addEventListener('submit', checkEwayPaymentForm, false)
        } else if (form.attachEvent) {
          //Old IE
          form.attachEvent('onsubmit', checkEwayPaymentForm)
        }

        function checkEwayPaymentForm (e) {
          var cardType = $.payform.parseCardType($('#EWAY_CARDNUMBER').val())
          var validCardNumber = true
          validCardNumber = $.payform.validateCardNumber($('#EWAY_CARDNUMBER').val())
          var validCardCVC = $.payform.validateCardCVC($('#EWAY_CARDCVN').val(), cardType)
          var validCardExpiry = $.payform.validateCardExpiry($('#EWAY_CARDEXPIRYMONTH').val(), $('#EWAY_CARDEXPIRYYEAR').val())

          $('#EWAY_CARDNUMBER').toggleInputError(!validCardNumber)
          $('#EWAY_CARDCVN').toggleInputError(!validCardCVC)
          $('#EWAY_CARDEXPIRYMONTH').toggleInputError(!validCardExpiry)
          // $("#EWAY_CARDEXPIRYYEAR").toggleInputError(!validCardExpiry);

          if (!validCardNumber || !validCardCVC || !validCardExpiry) {
            // Stop the form from submitting
            e.preventDefault()
            return false
          }

          $('#EWAY_CARDNUMBER').val($('#EWAY_CARDNUMBER').val().replace(/\s/g, ''))

          ewayAjax(e)

        }

        function ewayAjax (e) {
          // call eWAY to process the request
          eWAY.process(
            document.getElementById('eway-payment-form'),
            {
				<?php if (!$viewData['autoRedirect']) {
				?>
              autoRedirect: false,
				<?php
				} ?>

              onComplete: function (data) {
                // this is a callback to hook into when the requests completes and autoRedirect is false
                //$('.eway-payment-form').html('<?php echo vmText::_('VMPAYMENT_EWAY_UPDATE_CREDIT_CARD_SUCCESSFULL'); ?>');
                $('.eway-payment-form').hide()
                $('#eway-result').show()
                $('#eway-result-close').click(function () {
                  window.location.replace(data.RedirectUrl)
                })

              },
              onError: function (e) {
                // this is a callback you can hook into when an error occurs
                alert('There was an error processing the request')
              },
              onTimeout: function (e) {
                // this is a callback you can hook into when the request times out
                alert('The request has timed out.')
              }
            }
          )
          // Stop the form from submitting
          e.preventDefault()
        }
      })
	</script>
<?php } else {
	?>

	<script type="text/javascript">

      jQuery(document).ready(function ($) {

        $('#eway-result').hide()
        jQuery('#ewaySubmit').click(function () {
          var eway_card_selected = $(this).data('eway')
          console.log('eway_card_selected', eway_card_selected)
          if (eway_card_selected !== undefined) {

            request = {
              'option': 'com_virtuemart',
              'view': 'plugin',
              'type': 'vmpayment',
              'tmpl': 'raw',
              'name': 'eway',
              'action': 'deleteCard',
              'cardToDelete': eway_card_selected,
              'token': "<?php echo JSession::getFormToken() ?>",
            }
            $.ajax({
              type: 'POST',
              dataType: 'JSON',
              data: request,
              url: Virtuemart.vmSiteurl,
              beforeSend: function () {
                var object = {
                  data: {
                    msg: ''
                  }
                }
                Virtuemart.startVmLoading(object)
              },
              success: function (response) {
                Virtuemart.stopVmLoading()

                $('#eway-payment-delete').hide()
                $('#eway-result').show()
                $('#eway-result-close').click(function () {
                  window.location.replace( "<?php echo $viewData['reloadUrl'] ?>")
                })

              },
              error: function (e, t, n) {
                console.log('ewayDeleteAjax error')
                console.log(e)
                console.log(t)
                console.log(n)
                Virtuemart.stopVmLoading()
              }
            })

          }
        })

      })
	</script>
	<?php
}


?>