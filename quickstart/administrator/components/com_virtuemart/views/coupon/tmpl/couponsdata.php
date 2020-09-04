<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Coupon
* @author RickG, creative Momentum
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2020 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 9821 2018-04-16 18:04:39Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);

?>

<form action="index.php?option=com_virtuemart&view=coupon&layout=couponsdata" method="post" name="adminForm" id="adminForm">
	<div id="header">
		<div id="filterbox" style="padding: 10px 0 0;">
			<a style="padding: 8px;display: inline-block;margin: 0px 10px 10px 10px;" href="index.php?option=com_virtuemart&amp;view=coupon" class="btn btn-small button-delete"> <span class="icon-delete" aria-hidden="true"></span> Close Analytics </a>
			<table>
				<tr>
					<td align="left" width="100%">
						<?php echo vmText::_('COM_VIRTUEMART_FILTER'); ?>:
                        <input style="width: 130px;" type="text" name="filter_coupon" placeholder="Search Coupon" value="<?php echo vRequest::getVar('filter_coupon', ''); ?>" />
                        <input style="width: 130px;" type="text" name="filter_shopper" placeholder="Search Shopper" value="<?php echo vRequest::getVar('filter_shopper', ''); ?>" />
                        <input style="width: 130px;" type="text" name="filter_order_number" placeholder="Search Order Number" value="<?php echo vRequest::getVar('filter_order_number', ''); ?>" />
						<input style="width: 130px;" class="date_picker" type="text" name="filter_from_date" placeholder="Enter From Date" value="<?php echo vRequest::getVar('filter_from_date', ''); ?>" />
                        <input style="width: 130px;" class="date_picker" type="text" name="filter_to_date" placeholder="Enter To Number" value="<?php echo vRequest::getVar('filter_to_date', ''); ?>" />
						<button class="btn btn-small" onclick="this.form.submit();"><?php echo vmText::_('COM_VIRTUEMART_GO'); ?></button>
                        <button class="btn btn-small" onclick="document.adminForm.filter_coupon.value='';document.adminForm.filter_shopper.value='';document.adminForm.filter_order_number.value='';document.adminForm.filter_from_date.value='';document.adminForm.filter_to_date.value='';"><?php echo vmText::_('COM_VIRTUEMART_RESET'); ?></button>
					</td>
				</tr>
			</table>
            <div id="resultscounter" ><?php echo $this->pagination->getResultsCounter();?></div>
		</div>
	</div>
    <div id="editcell">
	    <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
	    <thead>
		<tr>
		    <th width="20%">
				Coupon Code
		    </th>
		    <th width="20%">
				Shopper
		    </th>
		    <th width="15%">
				Order Number
		    </th>
			<th width="15%">
				Order Total
		    </th>
			<th width="10%">
				Coupon Discount
		    </th>
		    <th width="20%">
				Date Used
		    </th>
		</tr>
	    </thead>
	    <?php
	    $k = 0;
	    for ($i=0, $n=count($this->coupons_data); $i < $n; $i++) {
		$row = $this->coupons_data[$i];
		?>
	    <tr class="row<?php echo $k; ?>">
			<td align="left">
				<?php echo $row->coupon_code;?>
			</td>
			<td align="left">
				<?php
					if(!$row->virtuemart_user_id){
						echo 'Non-Regsitered User<br> Customer Number:<br>'.$row->customer_number;
					} else 
						echo $row->name; ?>
			</td>
			<td align="left">
				<?php echo $row->order_number;?>
			</td>
			<td align="left">
				<?php echo $row->order_total;?>
			</td>
			<td align="left">
				<?php echo $row->coupon_discount;?>
			</td>
			<td align="left">
				<?php echo $row->created_on;?>
			</td>
	    </tr>
		<?php
		$k = 1 - $k;
	    }
	    ?>
	    <tfoot>
		<tr>
		    <td colspan="10">
			<?php echo $this->pagination->getListFooter(); ?>
		    </td>
		</tr>
	    </tfoot>
	</table>
    </div>

    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="controller" value="coupon" />
    <input type="hidden" name="view" value="coupon" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_( 'form.token' ); ?>
</form>

<script>
jQuery(document).ready(function(){
	jQuery( "body .date_picker" ).each(function(){
		jQuery(this).datepicker({
			dateFormat: "yy-mm-dd"
		 });
	});
});
</script>

<?php AdminUIHelper::endAdminArea(); ?>