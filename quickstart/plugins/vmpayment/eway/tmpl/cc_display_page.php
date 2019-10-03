<?php
defined('_JEXEC') or die();
/**
 * @author Valérie Isaksen
 * @version $Id: cc_display_page.php 10139 2019-09-12 18:50:21Z Milbo $
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
JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/plugins/vmpayment/eway/assets/css/eway.css');
if ($viewData['doCardCvn']) {
	vmJsApi::addJScript('/plugins/vmpayment/eway/assets/js/jquery.payform.min.js');
}
$selectedMaskedCardNumber = '';
$selectedMaskedCardCardCvn = '';
if ($viewData['index']) {
	$id = 'payment-id-' . $viewData['virtuemart_paymentmethod_id'] . '-' . $viewData['index'];
	$cvnId = 'cvn-id-' . $viewData['virtuemart_paymentmethod_id'] . '-' . $viewData['index'];
	$ccnId = 'ccn-id-' . $viewData['virtuemart_paymentmethod_id'] . '-' . $viewData['index'];
	$clickId = 'click-id-' . $viewData['virtuemart_paymentmethod_id'] . '-' . $viewData['index'];
	$dynClick = $viewData['dynUpdate'];
	$dynRadio = '';
	if ($viewData['checked']) {
		$selectedMaskedCard = $viewData['maskedCard'];
		$selectedMaskedCardCardCvn = $viewData['CardCvn'];
	}
} else {
	$id = 'payment-id-' . $viewData['virtuemart_paymentmethod_id'];
	$cvnId = '';
	$dynClick = '';
	$dynRadio = $viewData['dynUpdate'];
}


?>


<span class="vmpayment"><label for="<?php echo $id ?>">
		<input type="radio" <?php echo $dynRadio ?>
			   class="eway-select eway-select-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
			   name="virtuemart_paymentmethod_id"
			<?php if ($viewData['doCardCvn']) { ?>
				data-eway='<?php echo $viewData['maskedCard'] ?>'
				data-ewayindex="<?php echo $viewData['index'] ?>"
			<?php } ?>
			   id="<?php echo $id ?>"
			   value="<?php echo $viewData['virtuemart_paymentmethod_id'] ?>" <?php echo $viewData['checked'] ?> >

		<?php echo $viewData['pluginName'] ?>
		<?php echo $viewData['costDisplay'] ?>
		<?php if ($viewData['sandbox']) { ?>
			<span style="color:red;font-weight:bold">Sandbox (<?php echo $viewData['virtuemart_paymentmethod_id'] ?>
				)</span>
		<?php } ?>
</label>
	<?php if ($viewData['doCardCvn']) { ?>
		<input type="hidden" <?php echo $dynClick ?>
			   name="virtuemart_paymentmethod_id_click"
			   id="<?php echo $clickId ?>"
			   value="<?php echo $viewData['virtuemart_paymentmethod_id'] ?>">

		<div class="eway-display-group">
				<input type="hidden"
					   id="<?php echo $ccnId ?>"
					   name="ccn-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
					   value="<?php echo $viewData['maskedCardNumber'] ?>"
				>
				<label for="<?php echo $cvnId ?>" class="eway-display-label">
					<?php echo vmText::_('VMPAYMENT_EWAY_PAYMENT_CVN') ?></label>
					<input type="tel"
						   id="<?php echo $cvnId ?>"
						   name="cardcvn-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
						   class="eway-display-input"
						   data-issueNumber="<?php echo $viewData['cardcvn'] ?>"
						   placeholder="123" maxlength="4"
						   value="<?php echo $selectedMaskedCardCardCvn ?>"
					/>
				<span
						data-eway='<?php echo $viewData['maskedCard'] ?>'
						class="eway-edit-card button"><?php echo vmText::_('VMPAYMENT_EWAY_EDIT_CREDIT_CARD') ?>
						</span>
				<span data-eway='<?php echo $viewData['maskedCard'] ?>'
					  data-ewayindex="<?php echo $viewData['index'] ?>"
					  class="eway-delete-card button"><?php echo vmText::_('VMPAYMENT_EWAY_DELETE_CREDIT_CARD') ?></span>
				</div>
		</div>
	<?php } ?>
	</span>

<?php if ($viewData['doCardCvn'] && $viewData['addScript']) { ?>
	<input type="hidden" name="eway-selected-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
		   id="eway-selected-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
		   value="<?php echo $selectedMaskedCard ?>"/>
	<input type="hidden" name="eway-selected-cvn-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
		   id="eway-selected-cvn-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>"
		   value="<?php echo $selectedMaskedCardCardCvn ?>"/>


	<script>
      jQuery(document).ready(function ($) {
        jQuery("#eway-selected-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>").val()
        jQuery('.eway-display-input').focus(function () {
          $('.eway-select').prop('checked', false)
        })

        jQuery("input.eway-select-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>").click(function () {
          var eway_selected = $(this).data('eway')
          var ewayindex = $(this).data('ewayindex')
          var cardCvnIndex = "#cvn-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>-"
          cardCvnIndex = cardCvnIndex + ewayindex
          var cardCvnInput = $(cardCvnIndex).val()

          var ccnIndex = "#ccn-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>-"
          cardCcnIndex = ccnIndex + ewayindex
          var cardCcnInput = $(cardCcnIndex).val()
          var cardType = $.payform.parseCardType(cardCcnInput)

          var validCardCVC = $.payform.validateCardCVC(cardCvnInput, cardType)
          $('.eway-display-group').removeClass('eway-error')

          $('#payment-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>' + '-' + ewayindex).prop('checked', false)
          $(cardCcnIndex).parent('.eway-display-group').toggleClass('eway-error', !validCardCVC)

          if (eway_selected !== undefined && validCardCVC) {
            $("#eway-selected-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>").val(eway_selected)
            $("#eway-selected-cvn-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>").val(cardCvnInput)

            $('#payment-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>' + '-' + ewayindex).prop('checked', true)
            $('#click-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>' + '-' + ewayindex).trigger('click')
          }
        })

      })
	</script>

	<script>
      jQuery(document).ready(function ($) {
        jQuery('.eway-delete-card').click(function () {
          var eway_card_selected = $(this).data('eway')
          var ewayindex = $(this).data('ewayindex')

          if (eway_card_selected !== undefined) {
            $('#eway_card_selected').val(eway_card_selected)
            $('.eway-display-group').removeClass('eway-error').html()

            request = {
              'option': 'com_virtuemart',
              'view': 'plugin',
              'type': 'vmpayment',
              'tmpl': 'raw',
              'name': 'eway',
              'action': 'deleteCardConfirm',
              'cardToDelete': eway_card_selected,
              'token': "<?php echo JSession::getFormToken() ?>",
            }
            ewayDeleteConfirmAjax(request, ewayindex)

          }
        })

        jQuery('.eway-edit-card').click(function () {
          var eway_card_selected = $(this).data('eway')
          var ewayindex = $(this).data('ewayindex')
          console.log(eway_card_selected)
          console.log(ewayindex)
          if (eway_card_selected !== undefined) {

            $('.eway-display-group').removeClass('eway-error')

            request = {
              'option': 'com_virtuemart',
              'view': 'plugin',
              'type': 'vmpayment',
              'tmpl': 'raw',
              'name': 'eway',
              'action': 'updateCard',
              'cardToUpdate': eway_card_selected,
              'redirectURL': Virtuemart.vmSiteurl + "<?php echo vmURI::getCurrentUrlBy('get') ?>",
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
                $.fancybox({
                  'titlePosition': 'inside',
                  'transitionIn': 'fade',
                  'transitionOut': 'fade',
                  'changeFade': 'fast',
                  'autoCenter': true,
                  'closeBtn': false,
                  'showCloseButton': false,
                  'enableEscapeButton': false,
                  'hideOnOverlayClick': false,
                  'closeClick': false,
                  'content': response
                })
              },
              error: function (e, t, n) {
                console.log(e)
                console.log(t)
                console.log(n)
                Virtuemart.stopVmLoading()
              }
            })

          }
        })

        var ewayDeleteConfirmAjax = function (request, ewayindex) {
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
              $.fancybox({
                'titlePosition': 'inside',
                'transitionIn': 'fade',
                'transitionOut': 'fade',
                'changeFade': 'fast',
                'autoCenter': true,
                'closeBtn': false,
                'showCloseButton': false,
                'enableEscapeButton': false,
                'hideOnOverlayClick': false,
                'closeClick': false,
                'content': response
              })

              console.log('ewayDeleteConfirmAjax success')



              $('#payment-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>' + '-' + ewayindex).prop('checked', false)
              $('#click-id-<?php echo $viewData['virtuemart_paymentmethod_id'] ?>' + '-' + ewayindex).trigger('click')
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
	</script>


<?php } ?>
