<?php
defined ('_JEXEC') or     die('Direct Access to ' . basename (__FILE__) . ' is not allowed.');
/**
 * Downlodable media plugin for Product
 *
 * @version $Id:
 * @package VirtueMart
 * @subpackage Plugins - istraxx download simple
 * @author Max Milbers
 * @copyright Copyright (C) 2012 - 2018 iStraxx UG (haftungsbeschränkt). All rights reserved.
 * @license LGPLv3
 *
 */

class Istraxx_downloader_simple {

	static function redirecter () {
		$app = JFactory::getApplication();
		$app ->redirect(JURI::base() .'index.php?option=com_virtuemart&view=orders');
	}

	static function prepareCheckSendFileStore($plugin, $regkey, $name, $tablename){

		$db = JFactory::getDbo ();

		$err = '';
		$validDownload = true;

		$spl = explode(':',$regkey);
		if(count($spl)==4){

			$dlkey = preg_replace ('~[^a-z0-9_]+~i', '', $spl[1]);;
			$media_idR = (int)$spl[2];
			$dloid = (int)$spl[3];
			vmdebug('By regkey',$spl);
		} else {
			//$dlkey = vRequest::getCmd ('dlkey', false);
			//$dlkey = base64_decode ($dlkey);
			$dlkey = preg_replace ('~[^a-z0-9_]+~i', '', $regkey);
			$dloid = vRequest::getInt ('oid', false);
			$media_idR = vRequest::getInt('media_id',0);
			vmdebug('By dlkey',$spl);
		}

		if (!$order = self::getvalidItemId ($dlkey, $dloid)) {
			$err .= vmInfo (vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_FOUND'));
			vmdebug('There was no media for the dlkey '.$dlkey.' and dloid '.$dloid);
			$validDownload = false;
		}

		$order_status = $order['order_status'];
		$order_item_status = $order['order_item_status'];
		$productId = $order['virtuemart_product_id'];


		$pM = VmModel::getModel('product');
		$product = $pM->getProduct($productId);
		foreach($product->customfields as $field){
			if(empty($field->custom_element) or $field->custom_element!=$name) continue;

			if($field->media_id==$media_idR){
				$free_download = $field->free_download;
				break;
			}
		}

		$media_id = $media_idR;
		$os_download = array('C','S');
		$maxloads = 2;
		if (!empty($free_download) or (in_array($order_status, $os_download) and in_array($order_item_status, $os_download) )) {

			$date = getdate();
			$intTocday = $date[0];


			//Now lets check if the client is still allowed to download by the restrictions
			if ($maxloads != 0) {

				$q = 'SELECT * FROM `' . $tablename . '` WHERE `virtuemart_order_item_id` = "' . $dloid . '" AND `errorcode` = "0" ';

				$q .= 'ORDER BY created_on ASC';

				$db->setQuery ($q);
				$result = $db->loadAssocList ();
				$count = (int)count ($result);

				if ($count > 0) {
					if (!empty($maxloads)) {
						if (($count ) >= $maxloads) {
							$err .= vmWarn ('VMCUSTOM_ISTRAXX_DOWNLOAD_MAXDOWNLOADS_REACHED',$maxloads,$count);
							$validDownload = false;
						} else {
							//vmInfo('VMCUSTOM_ISTRAXX_DOWNLOAD_DOWNLOADED',$count);
						}
					}

				}
			}

			if(!empty($media_idR) and $media_idR!=$media_id){
				vmdebug('Hacking attempt?',$media_idR,$media_id);
				$err .= vmError('Hacking attempt? requested media id '.$media_id.', we give '.$media_id,'You got logged!');
			}
			$media = self::getMedia ($media_id);

			if (!$media) {
				$err .= vmError(vmText::_ ('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_SET'));
				$validDownload = false;
			} else if (!file_exists ($media->file_url)) {
				$err .= vmError(vmText::_('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_FOUND').' URL: '.$media->file_url,vmText::_('VMCUSTOM_ISTRAXX_DOWNLOAD_NO_FILE_FOUND'));
				$validDownload = false;
			}
			else {
				if (empty($maxspeed)) {
					$maxspeed = 1000;
				}
				if (!isset($stream)) {
					$stream = 0;
				}
			}

			//Insert new entry in plugin internal table, to store tries of downloading
			$data['id'] = 0;
			$data['virtuemart_order_item_id'] = $dloid;
			$data['errorcode'] = -1;
			$data['message'] = $err;

			$t = new ShopFunctions();
			if(method_exists($t,'getClientIP')){
				$data['client_ip'] = ShopFunctions::getClientIP();
			} else {
				$data['client_ip'] = $_SERVER['REMOTE_ADDR'];
			}

			$maskIP = VmConfig::get('maskIP','last');
			if($maskIP=='last'){
				$rpos = strrpos($data['client_ip'],'.');
				$data['client_ip'] = substr($data['client_ip'],0,($rpos+1)).'xx';
			}

			if($storeAnyTry=false) $plugin->storePluginInternalDataPr ($data);

			if(!$validDownload){
				return false;
			} else{

				self::downloadFile($media->file_url,$maxspeed,$stream);

				$data['errorcode'] = connection_status ();

				$rdspos = strrpos($media->file_url,DS);
				if($rdspos!==false){
					$name = substr($media->file_url,$rdspos+1);
				}

				$data['message'] = 'OK, media_id '.$media_id.' '.$name;
				//Update plugin internal table
				$plugin->storePluginInternalDataPr ($data);

				if($order_item_status!='S'){
					$orderdata = new stdClass();
					$orderdata->virtuemart_order_id = $order['virtuemart_order_id'];
					$orderdata->order_status = 'S';
					$orderModel = VmModel::getModel ('orders');
					$orderModel->updateSingleItem ($dloid, $orderdata);
				}

				return self::redirecter();
				jExit();
			}

		}
		else {
			vmInfo (vmText::sprintf('VMCUSTOM_ISTRAXX_DOWNLOAD_PAYMENT_NOT_CONFIRMED',$order_item_status));
			vmdebug('Not confirmed? '.$order_status.' '.$order_item_status);
			return self::redirecter();
			//return false;
		}

		return self::redirecter();
	}

	/**************************
	 ** Specific function for *
	 ** downloadable files    *
	 **************************/

	/*
	 * return order
	*/
	static function getOrderByItemId ($id) {

		$db = JFactory::getDBO ();
		$q = 'SELECT * FROM `#__virtuemart_orders` join `#__virtuemart_order_items` using ( `virtuemart_order_id`)'
		. ' WHERE `virtuemart_order_item_id` = ' . (int)$id;
		$db->setQuery ($q);
		return $db->loadObject ();
	}

	/*
	 * return valid
	*/
	static function getvalidItemId ($dlkey, $dloid) {

		$db = JFactory::getDBO ();
		$q = 'SELECT `oi`.`order_status` as order_item_status, `o`.`order_status`, `o`.`virtuemart_order_id`, `oi`.`virtuemart_order_id`,
		`oi`.`virtuemart_product_id` FROM `#__virtuemart_order_items` as
		`oi` join `#__virtuemart_orders` as `o` using ( `virtuemart_order_id`)
		WHERE `o`.`order_pass` = "' . $dlkey . '" AND `oi`.`virtuemart_order_item_id` = "' . $dloid . '" ';

		$db->setQuery ($q);
		vmdebug ('getvalidItemId ' . $q);
		return $db->loadAssoc ();
	}

	static function getMedias(){
		$db = JFactory::getDBO ();
		$q = 'SELECT m.* FROM `#__virtuemart_medias` as m '
		. 'WHERE `file_is_forSale` = 1 AND `published`="1" ';
		$user = JFactory::getUser();
		if(!$user->authorise('core.admin','com_virtuemart') and !$user->authorise('core.manager','com_virtuemart')){
			$vendorId = VmConfig::isSuperVendor();
			$q .= ' AND (`virtuemart_vendor_id` = "'.$vendorId.'" OR `shared`="1")';
		}
		$app = JFactory::getApplication();
		$q .= ' ORDER BY `created_on` DESC';
		//if(!$app->isSite()){
		$db->setQuery ($q);
		$result = $db->loadObjectList ();
		foreach($result as $item){
			$rdspos = strrpos($item->file_url,DS);
			if($rdspos===false and DS!='/'){
				$rdspos = strrpos($item->file_url,'/');
			}
			if($rdspos!==false){
				$name = substr($item->file_url,$rdspos+1);
				$item->file_url = VmConfig::get('forSale_path',0).$name;
			}

		}
		return $result;
		//}
		return false;
	}

	static function getMedia ($media_id) {

		if (empty($media_id)) return false;

		$db = JFactory::getDBO ();
		$q = 'SELECT m.* FROM `#__virtuemart_medias` as m '
		. 'WHERE `file_is_forSale` = 1 AND `published`="1" ';

		$q .= ' AND m.`virtuemart_media_id`=' . (int)$media_id;
		$db->setQuery ($q);
		$result = $db->loadObject ();

		if(!$result) return false;

		$rdspos = max( strrpos($result->file_url, '/'), strrpos($result->file_url, '\\') );
		if($rdspos!==false){
			$name = substr($result->file_url,$rdspos+1);
			$result->file_url = VmConfig::get('forSale_path',0).$name;
		}

		if (empty($result)) {
			vmError (500, 'Downloadplugin getMedia: File not found ');
			return FALSE;
		} else {
			return $result;
		}
	}

	/* Tutorial by AwesomePHP.com -> www.AwesomePHP.com */
	/* Function: download with resume/speed/stream options */

	/*
	 Parametrs: downloadFile(File Location, File Name,
	max speed, is streaming
	If streaming - movies will show as movies, images as images
	instead of download prompt
	*/

	static function downloadFile ($fileLocation, $maxSpeed = 100, $doStream = 0) {

		if (connection_status () != 0) {
			return (FALSE);
		}

		ini_set("zlib.output_compression", "Off");
		$fileName = basename ($fileLocation);

		$contentType = 'application/octet-stream';
		$expFile = explode ('.', $fileName);
		$endF = end ($expFile);
		$extension = strtolower ($endF);

		/* List of File Types */
		$fileTypes['swf'] = 'application/x-shockwave-flash';
		$fileTypes['pdf'] = 'application/pdf';
		$fileTypes['exe'] = 'application/octet-stream';
		$fileTypes['zip'] = 'application/zip';
		$fileTypes['doc'] = 'application/msword';
		$fileTypes['xls'] = 'application/vnd.ms-excel';
		$fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
		$fileTypes['gif'] = 'image/gif';
		$fileTypes['png'] = 'image/png';
		$fileTypes['jpeg'] = 'image/jpg';
		$fileTypes['jpg'] = 'image/jpg';
		$fileTypes['rar'] = 'application/x-rar-compressed';
		$fileTypes['epub'] = 'application/epub+zip';

		$fileTypes['ra'] = 'audio/x-pn-realaudio';
		$fileTypes['ram'] = 'audio/x-pn-realaudio';
		$fileTypes['ogg'] = 'audio/x-pn-realaudio';

		$fileTypes['wav'] = 'audio/wav';
		$fileTypes['wmv'] = 'video/x-msvideo';
		$fileTypes['avi'] = 'video/x-msvideo';
		$fileTypes['asf'] = 'video/x-msvideo';
		$fileTypes['divx'] = 'video/x-msvideo';

		$fileTypes['mid'] = 'audio/midi';
		$fileTypes['midi'] = 'audio/midi';
		$fileTypes['mp3'] = 'audio/mpeg';
		$fileTypes['mp4'] = 'audio/mpeg';
		$fileTypes['mpeg'] = 'video/mpeg';
		$fileTypes['mpg'] = 'video/mpeg';
		$fileTypes['mpe'] = 'video/mpeg';
		$fileTypes['mov'] = 'video/quicktime';
		$fileTypes['swf'] = 'video/quicktime';
		$fileTypes['3gp'] = 'video/quicktime';
		$fileTypes['m4a'] = 'video/quicktime';
		$fileTypes['aac'] = 'video/quicktime';
		$fileTypes['m3u'] = 'video/quicktime';

		if(!empty($fileTypes[$extension])){
			$contentType = $fileTypes[$extension];
		}

		//lets clean the buffer first
		ob_end_clean();
		ob_start();

		header ("Cache-Control: public");
		header ("Content-Transfer-Encoding: binary\n");
		header ('Content-Type: ' . $contentType);

		$contentDisposition = 'attachment';

		if ($doStream == 1) {
			/* extensions to stream */
			$array_listen = array('mp3', 'm3u', 'm4a', 'mid', 'ogg', 'ra', 'ram', 'wm',
			'wav', 'wma', 'aac', '3gp', 'avi', 'mov', 'mp4', 'mpeg', 'mpg', 'swf', 'wmv', 'divx', 'asf');
			if (in_array ($extension, $array_listen)) {
				$contentDisposition = 'inline';
			}
		}

		$agent = strtolower ($_SERVER['HTTP_USER_AGENT']);

		if (strpos ($agent, 'msie') !== FALSE) {
			$fileName = preg_replace ('/\./', '%2e', $fileName, substr_count ($fileName, '.') - 1);
		}

		header ("Content-Disposition: $contentDisposition; filename=\"$fileName\"");

		header ("Accept-Ranges: bytes");
		$range = 0;
		$size = filesize ($fileLocation);

		if (isset($_SERVER['HTTP_RANGE'])) {
			list($a, $range) = explode ("=", $_SERVER['HTTP_RANGE']);
			str_replace ($range, "-", $range);
			$size2 = $size - 1;
			$new_length = $size - $range;
			header ("HTTP/1.1 206 Partial Content");
			header ("Content-Length: $new_length");
			header ("Content-Range: bytes $range$size2/$size");
		}
		else {
			$size2 = $size - 1;
			header ("Content-Range: bytes 0-$size2/$size");
			header ("Content-Length: " . $size);
		}

		if ($size == 0) {
			die('Zero byte file! Aborting download');
		}
		//	set_magic_quotes_runtime(0);
		$fp = fopen ("$fileLocation", "rb");

		fseek ($fp, $range);
		$connection = connection_status ();



		while (!feof ($fp) and (connection_status () == 0)) {
			set_time_limit (0);
			print(fread ($fp, 1024 * $maxSpeed));
			flush ();
			ob_flush ();
			sleep (1);
		}
		fclose ($fp);

		return ;

	}
}