<?php
defined('_JEXEC') or die('');

/**
 * Renders the email for the user send in the registration process
 * @package	VirtueMart
 * @subpackage User
 * @author Max Milbers
 * @author Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2019 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version
 */
$li = '<br />';
?>
<html>
<head>
<style type="text/css">
	body, p, span, a, th, td {font-size: 12px;}
	table.html-email {margin: 10px auto;background-color: #FFFFFF;border: 1px solid #DAD8D8;}
	a.default:link, a.default:hover, a.default:visited {display: inline-block;line-height: 25px;margin: 10px ;padding: 3px 8px 1px 8px;background-color: #F2F2F2;color: #666666;border: 1px solid #CAC9C9;border-radius: 4px;-webkit-border-radius: 4px;-moz-border-radius: 4px;text-decoration: none;}
	a.default:hover {color: #888888;background-color: #F8F8F8;}
	table.html-email th {background-color: #DDDDDD;margin: 0px;padding: 10px;}
	.sectiontableentry2, .html-email th, .cart-summary th {background-color: #ccc;margin: 0px;padding: 10px;}
	.sectiontableentry1, .html-email td, .cart-summary td {background-color: #fff;margin: 0px;padding: 10px;}
</style>
</head>

<body style="background-color: #F2F2F2;word-wrap: break-word;">
<div style="background-color: #E6E6E6;" width="100%">
	<table style="margin: auto;" cellpadding="0" cellspacing="0"  >
	<tr>
		<td>
		<table class="html-email" style="width: 100%;border:1px solid #DAD8D8;padding: 0;margin: 10px;">
		<tr>
			<td style="width: 100%;">
				<?php echo vmText::sprintf('COM_VIRTUEMART_WELCOME_USER', $this->user->name); ?>
				<br />
				<?php
				if (!empty($this->activationLink)) {
				$activationLink = '<a class="default" href="' . JURI::root() . $this->activationLink . '">' . vmText::_('COM_VIRTUEMART_LINK_ACTIVATE_ACCOUNT') . '</a>';
				echo $li;
				echo $activationLink . $li;
				} ?>
			</td>
		</tr>
		</table>
		<table class="html-email" style="width: 100%;border:1px solid #DAD8D8;padding: 0;margin: 10px;">
		<tr>
			<th style="width: 100%;">
				<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_REGISTRATION_DATA') ?>
			</th>
		</tr>
		<tr>
			<td style="width: 100%;">
				<?php
				echo vmText::_('COM_VIRTUEMART_YOUR_LOGINAME') . $this->user->username . $li;
				echo vmText::_('COM_VIRTUEMART_YOUR_DISPLAYED_NAME') . $this->user->name . $li;
				if (!empty($this->password) ) {
					echo vmText::_('COM_VIRTUEMART_YOUR_PASSWORD')  . $this->password . $li;
				}
				echo $li.vmText::_('COM_VIRTUEMART_YOUR_ADDRESS')  . $li;
				foreach ($this->userFields['fields'] as $userField) {
					if (!empty($userField['value']) && $userField['type'] != 'delimiter' && $userField['type'] != 'hidden') {
						echo $userField['title'] . ': ' . $userField['value'] . $li;
					}
				} ?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
</div>
</body>
</html>