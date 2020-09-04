<?php
/**
 * Echo helper class
 *
 * Handles different types of output
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2014-2018 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Where type can be one of
 * 'warning' - yellow
 * 'notice' - blue
 * 'error' - red
 * 'message' (or empty) - green
 * This function shows an info message, the messages gets translated with vmText::,
 * you can overload the function, so that automatically sprintf is taken, when needed.
 * So this works vmInfo('COM_VIRTUEMART_MEDIA_NO_PATH_TYPE',$type,$link )
 * and also vmInfo('COM_VIRTUEMART_MEDIA_NO_PATH_TYPE');
 *
 * @author Max Milbers
 * @param string $publicdescr
 * @param string $value
 */


function vmInfo($publicdescr,$value=NULL){

	$app = JFactory::getApplication();

	$msg = '';
	$type = VmConfig::$mType;//'info';

	if(VmConfig::$maxMessageCount<VmConfig::$maxMessage){
		$lang = vmLanguage::getLanguage();
		if($value!==NULL){

			$args = func_get_args();
			if (count($args) > 0) {
				$args[0] = $lang->_($args[0]);
				$msg = call_user_func_array('sprintf', $args);
			}
		}	else {
			$msg = vmText::_($publicdescr);
		}
	}
	else {
		if (VmConfig::$maxMessageCount == VmConfig::$maxMessage) {
			$msg = 'Max messages reached';
			$type = 'warning';
			VmConfig::$maxMessageCount++;
		} else {
			return false;
		}
	}

	if(!empty($msg)){
		VmConfig::$maxMessageCount++;
		$app ->enqueueMessage($msg,$type);
	} else {
		vmTrace('vmInfo Message empty '.$msg);
	}

	return $msg;
}

/**
 * Informations for the vendors or the administrators of the store, but not for developers like vmdebug
 * @param      $publicdescr
 * @param null $value
 */
function vmAdminInfo($publicdescr,$value=NULL){

	if(VmConfig::$echoAdmin){

		$app = JFactory::getApplication();

		if(VmConfig::$maxMessageCount<VmConfig::$maxMessage){
			$lang = vmLanguage::getLanguage();
			if($value!==NULL){

				$args = func_get_args();
				if (count($args) > 0) {
					$args[0] = $lang->_($args[0]);
					VmConfig::$maxMessageCount++;
					$app ->enqueueMessage(call_user_func_array('sprintf', $args),VmConfig::$mType);
				}
			}	else {
				VmConfig::$maxMessageCount++;
				$publicdescr = $lang->_($publicdescr);
				$app ->enqueueMessage('Info: '.vmText::_($publicdescr),VmConfig::$mType);
			}
		}
		else {
			if (VmConfig::$maxMessageCount == VmConfig::$maxMessage) {
				$app->enqueueMessage ('Max messages reached', VmConfig::$mType);
				VmConfig::$maxMessageCount++;
			}else {
				return false;
			}
		}
	}

}

function vmWarn($publicdescr,$value=NULL){


	$app = JFactory::getApplication();
	$msg = '';
	if(VmConfig::$maxMessageCount<VmConfig::$maxMessage){
		$lang = vmLanguage::getLanguage();
		if($value!==NULL){

			$args = func_get_args();
			if (count($args) > 0) {
				$args[0] = $lang->_($args[0]);
				$msg = call_user_func_array('sprintf', $args);

			}
		}	else {
			$msg = $lang->_($publicdescr);
		}
	}
	else {
		if (VmConfig::$maxMessageCount == VmConfig::$maxMessage) {
			$msg = 'Max messages reached';
			VmConfig::$maxMessageCount++;
		} else {
			return false;
		}
	}

	if(!empty($msg)){
		VmConfig::$maxMessageCount++;
		$app ->enqueueMessage($msg,'warning');
		return $msg;
	} else {
		vmTrace('vmWarn Message empty');
		return false;
	}

}

/**
 * Shows an error message, sensible information should be only in the first one, the second one is for non BE users
 * @author Max Milbers
 */
function vmError($descr,$publicdescr=''){

	$msg = '';
	$lang = vmLanguage::getLanguage();
	$descr = $lang->_($descr);
	$adminmsg =  'vmError: '.$descr;
	if (empty($descr)) {
		vmTrace ('vmError message empty');
		return;
	}
	logInfo($adminmsg,'error');
	if(VmConfig::$maxMessageCount< (VmConfig::$maxMessage+5)){

		if(VmConfig::$echoAdmin){
			$msg = $adminmsg;
		} else {
			if(!empty($publicdescr)){
				$msg = $lang->_($publicdescr);
			}
		}
	}
	else {
		if (VmConfig::$maxMessageCount == (VmConfig::$maxMessage+5)) {
			$msg = 'Max messages reached';
			VmConfig::$maxMessageCount++;
		} else {
			return false;
		}
	}

	if(!empty($msg)){
		VmConfig::$maxMessageCount++;
		if(VmConfig::$echoDebug){
			VmConfig::$maxMessageCount++;
			echo $msg."\n";
		}else {
			VmConfig::$maxMessageCount++;
			$app = JFactory::getApplication();
			$app ->enqueueMessage($msg,'error');
		}
		return $msg;
	}

	return $msg;

}

/**
 * A debug dumper for VM, it is only shown to backend users.
 *
 * @author Max Milbers
 * @param unknown_type $descr
 * @param unknown_type $values
 */
function vmdebug($debugdescr,$debugvalues=NULL){
	//VmConfig::$_debug = true;
	if(VmConfig::$_debug ){
		if(VmConfig::$maxMessageCount<VmConfig::$maxMessage){
			if($debugvalues!==NULL){
				$args = func_get_args();
				if (count($args) > 1) {
					for($i=1;$i<count($args);$i++){
						if(isset($args[$i])){
							$debugdescr .=' Var'.$i.': <pre>'.print_r($args[$i],1).'<br />'.print_r(get_class_methods($args[$i]),1).'</pre>'."\n";
						}
					}

				}
			}

			if(VmConfig::$echoDebug){
				VmConfig::$maxMessageCount++;
				echo $debugdescr;
			} else if(VmConfig::$logDebug){
				logInfo($debugdescr,'vmdebug');
			}else {
				VmConfig::$maxMessageCount++;
				$app = JFactory::getApplication();
				$app ->enqueueMessage('<span class="vmdebug" >'.VmConfig::$maxMessageCount.' vmdebug '.$debugdescr.'</span>');
			}

		}
		else {
			if (VmConfig::$maxMessageCount == VmConfig::$maxMessage) {
				$app = JFactory::getApplication();
				$app->enqueueMessage ('Max messages reached', 'info');
				VmConfig::$maxMessageCount++;
			}
		}

	}

}

function vmTrace($notice,$force=FALSE, $args = 10){

	if($force || (VMConfig::showDebug() ) ){
		ob_start();
		echo '<pre>';
		debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,$args);

		echo '</pre>';
		$body = ob_get_contents();
		ob_end_clean();

		if(VmConfig::$logDebug){
			logInfo($body,$notice);
		}

		if(VMConfig::showDebug()){
			if(VmConfig::$echoDebug){
				echo $notice.' <pre>'.$body.'</pre>';
			}
			$app = JFactory::getApplication();
			$app ->enqueueMessage('<span class="vmdebug" >'.VmConfig::$maxMessageCount.' vmTrace '.$notice.' '.$body.'</span>');
		}
	}

}

function vmRam($notice,$value=NULL){
	vmdebug($notice.' used Ram '.round(memory_get_usage(TRUE)/(1024*1024),2).'M ',$value);
}

function vmRamPeak($notice,$value=NULL){
	vmdebug($notice.' memory peak '.round(memory_get_peak_usage(TRUE)/(1024*1024),2).'M ',$value);
}

function vmStartTimer($n='cur'){
	VmConfig::$_starttime[$n]['t'] = microtime(TRUE);
}

function vmSetStartTime($n='cur', $t = 0){
	if($t === 0){
		VmConfig::$_starttime[$n]['t'] = microtime(TRUE);
	} else {
		VmConfig::$_starttime[$n]['t'] = $t;
	}
}

function vmTime( $descr, $name='cur', $reset = true, $output = true){
	static $dt = 0.0;
	if (empty($descr)) {
		$descr = $name;
	}
	//$starttime = VmConfig::$_starttime ;
	if(empty(VmConfig::$_starttime[$name]['t'])){
		vmdebug('vmTime: '.$descr.' starting '.microtime(TRUE));
		VmConfig::$_starttime[$name] = array();
		VmConfig::$_starttime[$name]['t'] = microtime(TRUE);
	}
	else {
		$t = microtime (TRUE);
		$dt = ( $t - VmConfig::$_starttime[$name]['t'] );
		if(!isset(VmConfig::$_starttime[$name]['Z'])){
			VmConfig::$_starttime[$name]['Z'] = $dt;
		} else {
			VmConfig::$_starttime[$name]['Z'] += $dt;
		}

		if(!$reset) $dt = VmConfig::$_starttime[$name]['Z'];

		if ($name == 'cur') {
			if($output) vmdebug ('vmTime: ' . $descr . ' time consumed ' . $dt);
			VmConfig::$_starttime[$name]['t'] = microtime (TRUE);
		}
		else {
			$tmp = 'vmTime: ' . $descr . ': ' . $dt;
			if($output) vmdebug ($tmp);
			if($reset) VmConfig::$_starttime[$name]['t'] = $t;
		}
	}
	return $dt;
}

/**
 * logInfo
 * to help debugging Payment notification for example
 */
function logInfo ($text, $type = 'message') {

	static $file = null;
	//vmSetStartTime('logInfo');
	$head = false;

	if($file===null){


		$config = JFactory::getConfig();
		$log_path = $config->get('log_path', VMPATH_ROOT . "/log" );
		$file = $log_path . "/" . VmConfig::$logFileName . VmConfig::LOGFILEEXT;

		if (!is_dir($log_path)) {
			jimport('joomla.filesystem.folder');
			if (!JFolder::create($log_path)) {
				if (VmConfig::$echoAdmin){
					$msg = 'Could not create path ' . $log_path . ' to store log information. Check your folder ' . $log_path . ' permissions.';
					$app = JFactory::getApplication();
					$app->enqueueMessage($msg, 'error');
				}
				return;
			}
		}
		if (!is_writable($log_path)) {
			if (VmConfig::$echoAdmin){
				$msg = 'Path ' . $log_path . ' to store log information is not writable. Check your folder ' . $log_path . ' permissions.';
				$app = JFactory::getApplication();
				$app->enqueueMessage($msg, 'error');
			}
			return;
		}

		if (!JFile::exists($file)) {
			// blank line to prevent information disclose: https://bugs.php.net/bug.php?id=60677
			// from Joomla log file
			$head = "#\n";
			$head .= '#<?php die("Forbidden."); ?>'."\n";

		}
	}


	// Initialise variables.
	/*if(!class_exists('JClientHelper')) require(VMPATH_LIBS.'/joomla/client/helper.php');
	$FTPOptions = JClientHelper::getCredentials('ftp');
	if (!empty($FTPOptions['enabled'] == 0)){
		//For logging we do not support FTP. For loggin without file permissions using FTP, we need to load the file,..
		//append the text and replace the file. This cannot be fast per FTP and therefore we disable it.
	} else {*/

	$fp = fopen ($file, 'a');
	if ($fp) {
		if ($head) {
			fwrite ($fp,  $head);
		}

		fwrite ($fp, "\n" . JFactory::getDate()->format ('Y-m-d H:i:s'));
		fwrite ($fp,  " ".strtoupper($type) . ' ' . htmlspecialchars($text));
		fclose ($fp);
	} else {
		if (VmConfig::$echoAdmin){
			$msg = 'Could not write in file  ' . $file . ' to store log information. Check your file ' . $file . ' permissions.';
			$app = JFactory::getApplication();
			$app->enqueueMessage($msg, 'error');
		}
	}
	//}
	//vmTime('time','logInfo');
	return;

}