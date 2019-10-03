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
 
$item= $viewData[0];
$media = $viewData[1];

$html = '<div>';
//$html .= '<h3>' . $media->file_title . '</h3>';
$url = JURI::root () . 'administrator/index.php?option=com_virtuemart&view=plugin&name=istraxx_download_simple&media_id=' . $media->virtuemart_media_id ;
$html .= '<h4>'.JHTML::link ($url, vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_LINK_FOR') . ' ' . $media->file_title, array('title' => $media->file_title)).'</h4>';

$db = JFactory::getDbo();
$q = 'SELECT * FROM `' . $this->_tablename . '` WHERE `virtuemart_order_item_id` = "' . $item->virtuemart_order_item_id. '" ';
$q .= 'ORDER BY created_on DESC';

$db->setQuery ($q);
$result = $db->loadAssocList ();
$count = (int)count ($result);

if ($count > 0) {
	//JToolBarHelper::custom('toggle.errorcode.0', 'errorcode', 'no', vmText::_('0'), true);
	//JToolBarHelper::custom('toggle.errorcode.1', 'errorcode', 'no', vmText::_('1'), true);
	//vmdebug('My already done downloads',$result);
	$html .= 'Download log:';
	$html .= "<table>\n<thead>\n<tr>\n";
	//$html .= '<th>#</th>';
	$html .= '<th width="20px"><input type="checkbox" name="toggle" value="" onclick="checkAll('.count($result).')" /></th>';
	$html .= '<th>code</th>';
	$html .= '<th>client_ip</th>';
	$html .= '<th>date</th>';
	$html .= '<th>by User</th>';
	$html .= '<th>message</th>';
	$html .= "</tr>\n</thead>\n";
	$i=0;
	$html .= "<tbody>\n";

	//$userModel = VmModel::getModel('users');
	foreach($result as $k=>$row){
		$checked = JHTML::_('grid.id', $k , $row['virtuemart_order_item_id'],null,'virtuemart_order_item_id');
		$successful = $this->toggle($row['errorcode'], $row['id'],$row['virtuemart_order_item_id']);
		$html .= "<tr>\n";
		//$html .= '<td>'.$k.'</td>';
		$html .= '<td>'.$checked.'</td>';
		$html .= '<td>'.$successful.'</td>';
		$html .= '<td>'.$row['client_ip'].'</td>';
		$html .= '<td>'.$row['created_on'].'</td>';
		$by = 'anonymous';
		if($row['created_by']){
			//$userModel->setId($row['created_by']);
			$user = JFactory::getUser($row['created_by']);
			$by = $user ->name;
		}
		$html .= '<td>'.$by.'</td>';
		$html .= '<td>'.$row['message'].'</td>';
	}
	$html .= "</tbody>\n</table>";
} else {
	$html .= 'Not downloaded yet';
}

/*if(!empty($viewData[3]->free_download)){
    $free_download = true;
} else{
    $free_download = false;
}
$mydata = $viewData[2]->customfields;
if(!empty($mydata)){
    foreach($mydata as $mydata){
        if(strpos($mydata->customfield_params,'maxtime')){
            $allowmaxtime = (int)$viewData[3]->maxtime / 30.5; //fe display in months
        }
    }
}
$allowmaxtime = round($allowmaxtime,0);
if (!empty($free_download) or $item->order_status == 'S' or $item->order_status == 'C') {
    if(!empty($viewData[3]->show_subscHints)){
        if($allowmaxtime != 0){
            echo '</br><h4 style="color:green;margin:0 0 0 0">';
            echo VMTEXT::sprintf ('VMCUSTOM_ISTRAXX_DOWNLOAD_SUBSCRIPTION_MONTHS',$allowmaxtime);
            echo '</h4></br>';

        } else{
            echo '</br><h4 style="color:green;margin:0 0 0 0">';
            echo VMTEXT::sprintf ('VMCUSTOM_ISTRAXX_DOWNLOAD_SUBSCRPTION_VALID');
            echo'</h4>';
        }
    }
}else {
    if($item->order_status != 'P' AND $item->order_status != 'U'){
        if(!empty($viewData[3]->show_subscHints)){
            echo '</br><h4 style="color:red;margin:0 0 0 0">';
            echo VMTEXT::sprintf ('VMCUSTOM_ISTRAXX_DOWNLOAD_SUBSCRIPTION_EXPIRED_BE');
            echo '</h4></br>';
        } else {
            echo '</br><h4 style="margin:0 0 0 0">';
            echo VMTEXT::sprintf ('VMCUSTOM_ISTRAXX_DOWNLOAD_SUBSCRIPTION_AWAITING');
            echo '</h4>';
        }
    }
}*/

$html .= '</div>';

echo $html;