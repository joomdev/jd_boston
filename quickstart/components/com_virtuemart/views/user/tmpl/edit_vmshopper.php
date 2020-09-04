<?php
/**
 *
 * Modify user form view, User info
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_vmshopper.php 10163 2019-10-09 07:09:10Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if($this->userDetails->user_is_vendor or $this->allowRegisterVendor or !empty($this->userDetails->virtuemart_user_id)) {
?>

<fieldset>
	<legend class="userfields_info">
		<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_LBL') ?>
	</legend>
	<table class="adminForm user-details">
<?php	if(Vmconfig::get('multix','none')!=='none'){ ?>
		<?php if($this->userDetails->user_is_vendor or $this->allowRegisterVendor) { ?>
		<tr>
			<td class="key">
				<label for="virtuemart_vendor_id">
					<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_VENDOR') ?>:
				</label>
			</td>
		<?php if ($this->userDetails->user_is_vendor) { ?>
			<td>
				<?php echo $this->lists['vendors']; ?>
			</td>
		<?php } else if($this->allowRegisterVendor){ ?>
			<td>
				<?php echo VmHtml::checkbox ('user_is_vendor', $this->userDetails->user_is_vendor, 1, 0, '', 'user_is_vendor'); ?>
			</td>
		<?php } ?>
		</tr>
	<?php } ?>
<?php } ?>

		<?php if(!empty($this->userDetails->virtuemart_user_id)) { ?>
		<tr>
			<td class="key">
				<label for="customer_number">
					<?php echo vmText::_('COM_VIRTUEMART_USER_FORM_CUSTOMER_NUMBER') ?>:
				</label>
			</td>
			<td>
			 <?php
			 if(vmAccess::manager('user.edit')) { ?>
				<input type="text" class="inputbox" name="customer_number" id="customer_number" size="40" value="<?php echo  $this->lists['custnumber'];
					?>" />
			<?php } else {
				echo $this->lists['custnumber'];
			} ?>
			</td>
		</tr>
		<?php } ?>
		 <?php if($this->lists['shoppergroups'] and !empty($this->userDetails->virtuemart_user_id)) { ?>
		<tr>
			<td class="key">
				<label for="virtuemart_shoppergroup_id">
					<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP') ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['shoppergroups']; ?>
			</td>
		</tr>
		<?php } ?>
	</table>
</fieldset>
<?php } ?>