<?php
defined('_JEXEC') or die();

/**
 * Downlodable media plugin for Product
 *
 * @version $Id:
 * @package VirtueMart
 * @subpackage Plugins - istraxx download_simple
 * @author Max Milbers
 * @copyright Copyright (C) 2012-2018 iStraxx - All rights reserved.
 * @license LGPLv3
 *
 */


if(!empty($viewData[3]->free_download)){
	$free_download = $viewData[3]->free_download;
} else $free_download = 0;

$media = $viewData[0];
$url = $viewData[1];
$item = $viewData[2];

if (!empty($free_download) or $item->order_status == 'S' or $item->order_status == 'C') {
	$icon = '';

		$format = vRequest::getCmd('format',false);
		echo '<img src="'.$this->getPathUrlOnly('tmpl/disk.jpg',$format).'">';

		echo JHTML::link($url, $icon . ' ' . vmText::_('VMCUSTOM_ISTRAXX_DOWNLOAD_LINK_FOR') . ' ' . $media->file_title, array('title' => $media->file_title));

} else {
	$order_status_name = ShopFunctions::getOrderStatusName ($item->order_status);
	echo vmText::sprintf ('VMCUSTOM_ISTRAXX_DOWNLOAD_PAYMENT_NOT_CONFIRMED', '');
	echo '<span>' . $media->file_title . '</span>';
}
