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

$media = $viewData[0];
$field = $viewData[1];
$html = '';
if(empty($field->NotShow_filename)) {
	$html .= '<span>' . $media->file_title . '</span><br>';
}
$aveDayPerM = 30.4;

echo $html;

?>