<?php
defined('_JEXEC') or die();

/**
 * @author Valérie Isaksen
 * @version $Id: response_page.php 10139 2019-09-12 18:50:21Z Milbo $
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
<div id="eway-page">

	<h1><?php echo $viewData['pageTitle'] ?></h1>
	<?php if ($viewData['sandbox']) {
		echo '<p><span style="color:red;font-weight:bold">Your payment is set in sandbox mode. No real money is transferred and this is not suitable for live sites.</span></p>';
		echo '<p><span style="color:red;font-weight:bold"><a href="https://go.eway.io/s/article/Bank-Response-Codes-Responses-00-to-38" target="_blank">Bank Response Codes</a></span></p>';
		echo '<p><span style="color:red;font-weight:bold"><a href="https://go.eway.io/s/article/Transaction-Response-Codes" target="_blank">Transaction Response Codes</a></span></p>';
	}
	?>
	<div class="eway-fields">
		<div class="width25 floatleft"><?php echo vmText::_('VMPAYMENT_EWAY_RESPONSE_TRANSACTIONID') ?></div>
		<div class="width75"><?php echo $viewData['TransactionID'] ?></div>
	</div>
	<div class="eway-fields">
		<div class="width25 floatleft"><?php echo vmText::_('VMPAYMENT_EWAY_RESPONSE_MESSAGE') ?></div>
		<div class="width75 floatleft"><?php echo $viewData['ResponseMessage'] ?></div>
	</div>
	<div class="eway-fields">
			<a class="vm-button-correct"
							  href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number=' . $viewData["order_number"] . '&order_pass=' . $viewData["order_pass"], false) ?>">
				<?php echo vmText::_('COM_VIRTUEMART_ORDER_VIEW_ORDER'); ?></a>
	</div>

</div>




