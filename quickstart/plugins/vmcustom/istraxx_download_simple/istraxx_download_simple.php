<?php
defined ('_JEXEC') or     die('Direct Access to ' . basename (__FILE__) . ' is not allowed.');
/**
 * Downlodable media for sale plugin
 *
 * @version $Id:
 * @package VirtueMart
 * @subpackage Plugins -${PHING.GROUP} - ${PHING.FILENAME} - ${PHING.VERSION}
 * @author ${PHING.AUTHOR.MAX}
 * @copyright Copyright (C) 2012 - 2019 iStraxx UG (haftungsbeschr�nkt). All rights reserved.
 * @license LGPLv3
 */

if (!class_exists ('vmCustomPlugin')) {
	require(VMPATH_PLUGINLIBS . DS . 'vmcustomplugin.php');
}

class plgVmCustomIstraxx_download_simple extends vmCustomPlugin {

	var $_pname;

	function __construct (& $subject, $config) {

		$varsToPush = array(
			'media_id'         => array(0, 'int'),
			'free_download'    => array(0, 'int'),
			'name'             => array(0.0, 'char'),
			'reminder' => array('', 'char')
		);

		$this->setConfigParameterable ('customfield_params', $varsToPush);

		$this->_loggable = TRUE;
		$this->tableFields = array('id', 'virtuemart_order_item_id', 'client_ip', 'errorcode','message','virtuemart_product_id');

		parent::__construct ($subject, $config);

		$this->_tableId = 'id';
		$this->_tablepkey = 'id';
		JLoader::register('Istraxx_downloader_simple',JPATH_ROOT .'/plugins/vmcustom/istraxx_download_simple/istraxx_downloader_simple.php');

	}

	public function getVmPluginCreateTableSQL () {

		return $this->createTableSQL ('Virtual Goods Table');

	}

	function getTableSQLFields () {

		$SQLfields = array(	'id'       	=> 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
			'virtuemart_order_item_id'	=> 'int(1) UNSIGNED',
			'client_ip'					=> 'char(42)',
			'errorcode'					=> 'tinyint(1)',
			'message'					=> 'char(255)',
			'virtuemart_product_id'		=> 'int(1) UNSIGNED'
		);
		return $SQLfields;
	}

	function plgVmOnStoreInstallPluginTable ($plgType, $data, $table) {

		if($plgType!=$this->_psType){
			return false;
		}
		if(!empty($data['custom_element']) and $data['custom_element']!=$this->_name){
			return false;
		}
		$this->onStoreInstallPluginTable ($plgType, $data['custom_element']);

		if(is_object($table) and empty($table->is_cart_attribute) or $table->is_input=='0'){
			$table->is_input = 0;
			$table->is_cart_attribute = 1;
			$table->store();
		}

	}

	function plgVmDeclarePluginParamsCustomVM3(&$data){
		return $this->declarePluginParams('custom', $data);
	}

	function plgVmGetTablePluginParams($psType, $name, $id, &$xParams, &$varsToPush){
		return $this->getTablePluginParams($psType, $name, $id, $xParams, $varsToPush);
	}

	function plgVmOnProductEdit ($field, $product_id, &$row, &$html) {

		if ($field->custom_element != $this->_name) {
			return '';
		}

		$paramName = 'customfield_params';

		$html .= '<table>';

		/*JLoader::register('JFormFieldReminder', VMPATH_ROOT.'/plugins/vmcustom/istraxx_download_simple/fields/reminder.php');
		$html .= '<tr><td colspan="2">'.JFormFieldReminder::writeReminder($field->reminder, false, $field->virtuemart_custom_id).'</td></tr>';*/
		$html .='<tr>';
		if (!$medias = Istraxx_downloader_simple::getMedias ()) {
			$html .= '<td>'.vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_DOWNLOADABLE_MEDIAS_FOUND').'</td>';
			$html .= '<td></td>';
		}
		else {
			$attr='style= "width: 400px;"';
			$html .= '<td>'.vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_SELECT_MEDIA') .'</td> <td>'.		VmHTML::select ($paramName.'[' . $row . '][media_id]', $medias,	$field->media_id, $attr, 'virtuemart_media_id', 'file_title', FALSE)

			. '</td>';
		}

		$html .='</tr>';

		$html .= VmHTML::row('checkbox','VMCUSTOM_ISTRAXX_DOWNLOAD_FREE',$paramName.'[' . $row . '][free_download]',$field->free_download);

		$html .='</tr>';

		$html .= '</table>';

		return TRUE;
	}

	function plgVmOnViewCartVM3(&$product, &$productCustom, &$html) {
		if (empty($productCustom->custom_element) or $productCustom->custom_element != $this->_name) return false;

		static $alreadyHandledMedia = array();
		foreach($product->customfields as $field){
			if($field->custom_element == 'istraxx_download_simple' and !in_array($field->media_id,$alreadyHandledMedia)){

				if(!empty($field->media_id)){
					if ($media = Istraxx_downloader_simple::getMedia ($field->media_id)) {
						$html .= $this->renderByLayout('cart',array($media,$field ) );

						$alreadyHandledMedia[] = $field->media_id;
					}
				}
			}
		}

		return true;
	}

	function plgVmOnViewCartModuleVM3( &$product, &$productCustom, &$html) {
		//return $this->plgVmOnViewCartVM3($product,$productCustom,$html);
		if (empty($productCustom->custom_element) or $productCustom->custom_element != $this->_name) return false;

		static $alreadyHandledMediaMod = array();
		foreach($product->customfields as $field){
			if($field->custom_element == 'istraxx_download_simple' and !in_array($field->media_id,$alreadyHandledMediaMod)){

				if(!empty($field->media_id)){
					if ($media = Istraxx_downloader_simple::getMedia ($field->media_id)) {
						$html .= '<span>' . $media->file_title . '</span><br>';
						$alreadyHandledMediaMod[] = $field->media_id;
					}
				}
			}
		}

		return true;
	}

	function plgVmDisplayInOrderFEVM3( &$product, &$productCustom, &$html) {

		static $alreadyHandledMedia = array();
		if($productCustom->custom_element != $this->_name){
			return;
		}

		if (!$order = Istraxx_downloader_simple::getOrderByItemId ($product->virtuemart_order_item_id)) {
			vmdebug ('fileforsale plgVmDisplayInOrderFE ', $product);
			return;
		}

		//Attention, it can happen that a product is rendered 3 times in one call (invoice, mail vendor, mail user)
		$toVendor = vRequest::getInt('doVendor',-1);
		if($toVendor===-1){
			$alreadyHandledMedia= array();
		}
		$v =(int)$toVendor;
		//$h = $product->virtuemart_order_item_id.'.'.$productCustom->media_id;
		if(!isset($alreadyHandledMedia[$v])) $alreadyHandledMedia[$v] = array();
		/*if(isset($alreadyHandledMedia[$h])){
			unset($alreadyHandledMedia[$h]);
		}*/

		if(!in_array($productCustom->media_id,$alreadyHandledMedia[$v])){
			$alreadyHandledMedia[$v][]=$productCustom->media_id;
			$media = Istraxx_downloader_simple::getMedia ($productCustom->media_id);

			$sep = ':';
			$regkey = base64_encode ($order->order_number.$sep.$order->order_pass.$sep.$media->virtuemart_media_id.$sep.$product->virtuemart_order_item_id);

			$url = JURI::root () . 'index.php?option=com_virtuemart&view=plugin&name='.$this->_name.'&regkey=' . $regkey;
			//$url = JURI::root () . 'index.php?option=com_virtuemart&view=plugin&name=istraxx_download_simple&media_id=' . $media->virtuemart_media_id . '&dlkey=' . base64_encode ($order->order_pass) . '&oid=' . $product->virtuemart_order_item_id;

			$html .= $this->renderByLayout('order',array($media,$url,$product,$productCustom ) );

		}

		return TRUE;
	}

	function plgVmOnDisplayProductFEVM3(&$product,&$group) {

		if ($group->custom_element != $this->_name) return '';

		if(empty($group->media_id)) return false;
		if (!$media = Istraxx_downloader_simple::getMedia ($group->media_id)) {
			$group->display = vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_SET');
			return FALSE;
		}

		$product -> media = $media;
		$group->display .= $this->renderByLayout('default',array(&$product,&$group) );

		return true;
	}

	function plgVmDisplayInOrderBEVM3( &$product, &$productCustom, &$html) {

		if (empty($productCustom->custom_element) or $productCustom->custom_element != $this->_name) {
			return '';
		}

		if(!empty($productCustom->media_id)){
			$media = Istraxx_downloader_simple::getMedia ($productCustom->media_id);
			if($media){
				$html = $this->renderByLayout('dlist',array($product,$media));
				return true;
			}
		}
		$html = vmText::_('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_FOUND');
	}

	function toggle( $field, $id,$oid )
	{

		$imgY = 'tick.png';
		$imgX = 'publish_x.png';
		$img 	= $field ? $imgX: $imgY;

		$alt 	= $field ? vmText::_('COM_VIRTUEMART_PUBLISHED') : vmText::_('COM_VIRTUEMART_DISABLED');

		if(empty($field)){
			$tvalue = 1;
		} else {
			$tvalue = 0;
		}
		$link = 'index.php?option=com_virtuemart&view=plugin&type=vmcustom&name='.$this->_name.'&id='.$id.'&oid='.$oid.'&errorcode='.$tvalue ;

		return  '<a href="' .$link.'" >'.JHTML::_('image', 'admin/' .$img, $alt, null, true) .'</a>';

	}

	function plgVmOnSelfCallBE ($type, $name, &$render){

		if ($name != $this->_name || $type != 'vmcustom') {
			vmdebug( 'plgVmOnSelfCallBE '.$name.' '.$this->_name);
			return FALSE;
		}

		$user = JFactory::getUser();

		$media_id = vRequest::getInt('media_id',false);
		if($media_id){
			$media = Istraxx_downloader_simple::getMedia ($media_id);
			$superVendor = vmAccess::isSuperVendor();
			if( ($media->virtuemart_vendor_id>1 and $media->virtuemart_vendor_id==$superVendor) or vmAccess::manager('core') ){
				$this->params->maxspeed = 1000;
				$this->params->stream = 0;
				Istraxx_downloader_simple::downloadFile($media->file_url,$this->params->maxspeed,$this->params->stream);
				return true;
			}

		}

		if(!$user->authorise('core.admin','com_virtuemart') and !$user->authorise('core.manage','com_virtuemart')){
			return FALSE;
		}

		$db = JFactory::getDbo();

		$oid = vRequest::getInt('oid',0);
		$value = vRequest::getInt('errorcode',0);

		$id = vRequest::getInt('id',0);
		if(!$media_id and !empty($id) and !empty($oid) ){

			$q = 'UPDATE `' . $this->_tablename . '` SET `errorcode`= "'.$value.'" WHERE  `id` = "'.$id.'"';
			$db -> setQuery($q);
			$db -> query();
		}

		$db -> setQuery('SELECT `virtuemart_order_id` FROM #__virtuemart_order_items WHERE virtuemart_order_item_id = '.$oid);
		$orderId = $db->loadResult();
		$app = JFactory::getApplication();
		$app ->redirect(JURI::base() .'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$orderId);
	}


	function plgVmOnSelfCallFE ($type, $name, &$render) {

		if ($name != $this->_name || $type != 'vmcustom') {
			return FALSE;
		}
		//VmConfig::$echoDebug=1;
		$requKey = vRequest::getCmd ('regkey', false);
		if(!$requKey){
			$requKey = vRequest::getCmd ('dlkey', false);
			if(!$requKey){
				return false;
			}
		}

		$dlkey = base64_decode ($requKey);

		$sess = JFactory::getSession();

		//Lets block too many connections
		$date = getdate();
		$intTocday = $date[0];
		$requT = $sess->get('istraxxDLCrequested'.$dlkey);
		if($requT){

			$fvalidDate = $requT + 20;	//We allow requesting by the same downloadkey each 20 secs
			$diff = $fvalidDate - $intTocday;
			//vmdebug('plgVmOnSelfCallFE '.$hash.' '.($requT + 30).'-'.$intTocday.' = '.$diff);
			if($diff>0) {
				vmInfo('Too many requests');
				return false;
			}
		}

		$sess->set('istraxxDLCrequested'.$dlkey,$intTocday);

		$tablename = $this->_tablename;
		$name = $this->_name;
		Istraxx_downloader_simple::prepareCheckSendFileStore($this, $dlkey, $name, $tablename);
		return true;
	}

	function getPathUrlOnly($link,$format=false){

		$ds = '/';
		if($format=='path') {
			$ds = DS;
			$link = str_replace('/',DS,$link);
		}

		$link = trim($link,$ds);

		$base = 'plugins'.$ds.$this->_type.$ds.$this->_name;

		if($format=='path'){
			return VMPATH_SITE.DS.$base.DS.$link;
		} else if($format=='mail'){
			return JURI::root().$base.'/'.$link;
		} else {
			return JURI::root(true).'/'.$base.'/'.$link;
		}
	}

	function getOwnUrl(){

		$url = '/plugins/'.$this->_type.'/'.$this->_name;

		return $url;
	}

	function storePluginInternalDataPr(&$data){
		$this->storePluginInternalData($data);
	}

}

// No closing tag