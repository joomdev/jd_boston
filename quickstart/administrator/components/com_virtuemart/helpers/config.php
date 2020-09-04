<?php
/**
 * Configuration helper class
 *
 * This class provides some functions that are used throughout the VirtueMart shop to access configuration values.
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009-2018 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 */
defined('_JEXEC') or die('Restricted access');

/**
 * We use this Class STATIC not dynamically !
 */
class VmConfig {

	// instance of class
	private static $_jpConfig = NULL;
	public static $_debug = NULL;
	private static $_secret = NULL;
	public static $_starttime = array();
	public static $loaded = FALSE;

	public static $maxMessageCount = 0;
	public static $maxMessage = 300;
	public static $echoDebug = FALSE;
	public static $logDebug = FALSE;
	public static $logFileName = 'com_virtuemart';
	public static $echoAdmin = FALSE;
	const LOGFILEEXT = '.log.php';

	public static $langs = array();
	public static $langCount = 0;

	public static $vmlang = false;	//actually selected
	public static $vmlangTag = '';
	public static $vmlangSef = '';

	public static $defaultLang = false;
	public static $defaultLangTag = false;
	public static $jDefLang = false;
	public static $jDefLangTag = false;

	public static $mType = 'info';
	var $_params = array();
	var $_raw = array();
	public static $installed = false;
	public static $lazyLoad = null;

	private function __construct() {

		if(function_exists('mb_ereg_replace')){
			mb_regex_encoding('UTF-8');
			mb_internal_encoding('UTF-8');
		}

		ini_set('precision', 15);	//We need at least 20 for correct precision if json is using a bigInt ids
		//But 15 has the best precision, using higher precision adds fantasy numbers to the end, but creates also errors in rounding
		ini_set('serialize_precision',16);

		if(JVM_VERSION<3){
			self::$mType = 'info';
		} else {
			self::$mType = 'notice';
		}
	}

	static function getStartTime(){
		return self::$_starttime;
	}

	static function setStartTime($name,$value){
		self::$_starttime[$name] = $value;
	}

	static function getSecret(){
		return self::$_secret;
	}

	static function echoAdmin(){
		if(self::$echoAdmin===FALSE){
			self::$echoAdmin = vmAccess::manager('core.manage');
		}
	}

	static function showDebug($override=false){

		if(self::$_debug===NULL or $override!=false){
			if($override) {
				$debug = $override;
				$dev = $override;

			} else {
				$debug = VmConfig::get('debug_enable','none');
				$dev = VmConfig::get('vmdev','none');
			}

			//$debug = 'all';	//this is only needed, when you want to debug THIS file
			// 1 show debug only to admins
			self::$_debug = FALSE;
			if($debug === 'admin' and VmConfig::$echoAdmin){
				self::$_debug = TRUE;
			}
			// 2 show debug to anyone
			else if ($debug === 'all') {
					self::$_debug = TRUE;
			}

			if ($dev === 'all') {
				self::setErrRepDebug();
			} else if($dev === 'admin' and VmConfig::$echoAdmin){
				self::setErrRepDebug();
			} else if($dev === 'none'){
				self::setErrRepDefault();
			}
		}

		return self::$_debug;
	}

	static function setErrRepDefault($force=false){
		$jconfig = JFactory::getConfig();
		$errep = $jconfig->get('error_reporting');
		if ( $errep == 'default' or $force) {
			$ret[0] = ini_set('display_errors', 0);
			$ret[1] = error_reporting(E_ERROR | E_WARNING | E_PARSE);
		}
	}

	static function setErrRepDebug(){
		$ret[0] = ini_set('display_errors', '-1');
		$cVer = phpversion();

		if(version_compare($cVer,'5.4.0','<' )){
			$ret[1] = error_reporting( E_ALL );
		} else {
			$ret[1] = error_reporting( E_ALL & ~E_STRICT);
		}

		vmdebug('Show All Errors, PHP-Version '.$cVer);
	}


/**
	 * Ensures a certain Memory limit for php (if server supports it)
	 * @author Max Milbers
	 * @param int $minMemory
	 */
	static function ensureMemoryLimit($minMemory=0){

		if($minMemory === 0) $minMemory = VmConfig::get('minMemory','128M');
		$memory_limit = VmConfig::getMemoryLimit();

		if($memory_limit<$minMemory)  @ini_set( 'memory_limit', $minMemory.'M' );
	}

	static function getMemoryLimitBytes(){
		static $mLimit;
		if($mLimit===null){
			$mL = ini_get('memory_limit');
			$mLimit = 0;
			if(!empty($mL)){

				if($mL < 0){
					$mLimit = 2;
					$u = 'G';
				} else {
					$u = strtoupper(substr($mL,-1));
					$ord = ord($u);
					//Just numbers
					if (($ord>=48)&&($ord<=57)) {
						$mLimit = (int)($mL);
					} else {
						$mLimit = (int)substr($mL,0,-1);
					}
				}

				if($mLimit>0){
					if($u == 'M'){
						$mLimit *= 1048576;
					} else if($u == 'G'){
						$mLimit *= 1073741824;
					} else if($u == 'K'){
						$mLimit *= 1024;
					}

					$mTest = $mLimit - 5242880; // 5 MB reserve

					if($mTest<=0){
						$m = 'Increase your php memory limit, which is much too low to run VM, your current memory limit is set as '.$mL.' = '.$mLimit.'B';
						vmError($m,$m);
					}
				}
			}

			if($mLimit<=0) $mLimit = 2142240768;
			vmdebug('My Memory Limit in Bytes '.$mLimit);
		}

		return $mLimit;
	}

	/**
	 * Returns the PHP memory limit of the server in MB, regardless the used unit
	 * @author Max Milbers
	 * @return float|int PHP memory limit in MB
	 */

	static function getMemoryLimit(){
		static $mLimit;
		if($mLimit===null){
			$mL = ini_get('memory_limit');
			$mLimit = 0;
			if(!empty($mL)){
				$u = strtoupper(substr($mL,-1));
				$mLimit = (int)substr($mL,0,-1);
				if($mLimit>0){

					if($u == 'M'){
						//$mLimit = $mLimit * 1048576;
					} else if($u == 'G'){
						$mLimit *= 1024;
					} else if($u == 'K'){
						$mLimit *= 0.0009765625; //*1024
					} else {
						$mLimit = $mLimit / 1048576.0;
					}
					$mLimit = (int) $mLimit - 5; // 5 MB reserve
					if($mLimit<=0){
						$mLimit = 1;
						$m = 'Increase your php memory limit, which is must too low to run VM, your current memory limit is set as '.$mL.' ='.$mLimit.'MB';
						vmError($m,$m);
					}
				}
			}

			if($mLimit<=0) $mLimit = 2048;
			vmdebug('My Memory Limit in MB '.$mLimit);
		}

		return $mLimit;
	}



	static function ensureExecutionTime($minTime=0){

		if($minTime === 0) $minTime = (int) VmConfig::get('minTime',120);
		$max_execution_time = self::getExecutionTime();
		if((int)$max_execution_time<$minTime) {
			@ini_set( 'max_execution_time', $minTime );
		}
	}

	static function getExecutionTime(){
		$max_execution_time = (int) ini_get('max_execution_time');
		if(empty($max_execution_time)){
			$max_execution_time = (int) VmConfig::get('minTime',120);
		}
		return $max_execution_time;
	}

	static private $cache;

	/**
	 * @deprecated
	 * @param string $group
	 * @param string $handler
	 * @param null $storage
	 * @return mixed
	 */
	public static function getCache($group = '', $handler = 'callback', $storage = null, $site = true)
	{
		$hash = $group . $handler . $storage;

		if (isset(self::$cache[$hash]))
		{
			return self::$cache[$hash];
		}

		$handler = ($handler == 'function') ? 'callback' : $handler;

		if($site){
			$p = VMPATH_ROOT;
		} else {
			$p = VMPATH_ADMINISTRATOR;
		}
		$conf = JFactory::getConfig();
		$options = array(
		'defaultgroup'	=> $group,
		'caching'		=> true,
		'cachebase'		=> $conf->get('cache_path', $p . '/cache')
		);

		if (isset($storage)) {
			$options['storage'] = $storage;
		} else {
			$options['storage'] = $conf->get('cache_handler', '');
		}

		$cache = JCache::getInstance($handler, $options);

		self::$cache[$hash] = $cache;

		return self::$cache[$hash];
	}
	

	/*
	* Set default language tag for translatable table
	* @deprecated please use vmLanguage::setLanguageByTag
	* @author Max Milbers
	* @return string valid langtag
	*/
	static public function setdbLanguageTag($siteLang = false) {
		return vmLanguage::setLanguageByTag($siteLang);
	}

	/**
	 * @deprecated please use vmLanguage::loadJLang
	 */
	static public function loadJLang($name, $site = false, $tag = 0, $cache = true){
		return vmLanguage::loadJLang($name, $site, $tag, $cache);
	}

	/**
	 * @deprecated please usevmLanguage::loadModJLang
	 */
	static public function loadModJLang($name){
		return vmLanguage::loadModJLang($name);
	}

	/**
	 * Loads the configuration and works as singleton therefore called static. The call using the program cache
	 * is 10 times faster then taking from the session. The session is still approx. 30 times faster then using the file.
	 * The db is 10 times slower then the session.
	 *
	 * Performance:
	 *
	 * Fastest is
	 * Program Cache: 1.5974044799805E-5
	 * Session Cache: 0.00016094612121582
	 *
	 * First config db load: 0.00052118301391602
	 * Parsed and in session: 0.001554012298584
	 *
	 * After install from file: 0.0040450096130371
	 * Parsed and in session: 0.0051419734954834
	 *
	 *
	 * Functions tests if already loaded in program cache, session cache, database and at last the file.
	 *
	 * Load the configuration values from the database into a session variable.
	 * This step is done to prevent accessing the database for every configuration variable lookup.
	 *
	 * @author Max Milbers
	 * @param $force boolean Forces the function to load the config from the db
	 */
	static public function loadConfig($force = FALSE,$fresh = FALSE, $lang = true, $exeTrig = true) {

		static $execTrigger = true;
		static $defined = false;
		static $iniLang = true;

		if(!$defined){
			JLoader::register('vmDefines', JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmdefines.php');
//In WP, we run the define, when we render vm, in Joomla we have to run them here
			if(defined('JVERSION')){
				VmDefines::defines(JFactory::getApplication()->getName());
				require_once(VMPATH_ADMIN .'/helpers/vmecho.php');
			}
			$defined = true;
		}


		if($fresh){
			self::$_jpConfig = new VmConfig();
			if($lang and $iniLang){
				vmLanguage::initialise();
				$iniLang = false;
			}
			return self::$_jpConfig;
		}

		vmSetStartTime('loadConfig');
		$app = JFactory::getApplication(vmDefines::$_appId);
		if(!$force){
			if(!empty(self::$_jpConfig) && !empty(self::$_jpConfig->_params)){

				if($lang and $iniLang){
					vmLanguage::initialise();
					$iniLang = false;
				}

				if($exeTrig and $execTrigger){
					// try plugins
					$isSite = VmConfig::isSite();
					self::importVMPlugins('vmuserfield');
					if($isSite){

						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'plgVmInitialise', array() );
					}
					$execTrigger = false;
				}
				return self::$_jpConfig;
			}
		}

		self::$_jpConfig = new VmConfig();

		$configTable  = VirtueMartModelConfig::checkConfigTableExists();

		$db = JFactory::getDbo();

		self::$installed = true;
		$install = vRequest::getInt('install',false);
		$redirected = vRequest::getInt('redirected',false);
		$link='';
		$msg = '';

		if(empty($configTable) ){
			self::$installed = false;
			vmLanguage::initialise();
			vmLanguage::loadJLang('com_virtuemart');

			$q = 'SELECT `element` FROM `#__extensions` WHERE type = "language" and enabled = "1" and state="0"';
			$db->setQuery($q);
			$knownLangs = $db->loadColumn();
			//vmdebug('Selected language '.$selectedLang.' $knownLangs ',$knownLangs);

			if(!VmConfig::isSiteByApp() and !$redirected and !in_array(vmLanguage::$currLangTag,$knownLangs)){
				$msg = 'Install your selected language <b>'.vmLanguage::$currLangTag.'</b> in <a href="'.$link.'">joomla language manager</a>';
				$app->enqueueMessage($msg);
			}

			self::$installed = VirtueMartModelConfig::checkVirtuemartInstalled();
			if(!self::$installed){
				if(!$redirected and !$install){
					$link = 'index.php?option=com_virtuemart&view=updatesmigration&redirected=1&nosafepathcheck=1';

					if(VmConfig::isSiteByApp()){
						$link = JUri::root(true).'/administrator/'.$link;
					} else {
						if(empty($msg)) $msg = 'COM_VM_INSTALLATION_INFO';
					}
				}
			}
		} else {
			$query = ' SELECT `config` FROM `#__virtuemart_configs` WHERE `virtuemart_config_id` = "1";';
			$db->setQuery($query);
			self::$_jpConfig->_raw = $db->loadResult();
			//vmTime('time to load config','loadConfig');
		}

		if(empty(self::$_jpConfig->_raw)){
			vmLanguage::initialise();
			$_value = VirtueMartModelConfig::readConfigFile();
			if (!$_value) {
				vmError('Serious error, config file could not be read');
				return FALSE;
			}
			$_value = join('|', $_value);
			self::$_jpConfig->_raw = $_value;
			self::$_jpConfig->setParams(self::$_jpConfig->_raw);
			if($raw = VirtueMartModelConfig::storeConfig( self::$_jpConfig->toString() )){
				self::$_jpConfig->_raw = $raw;
			}
		} else {
			self::$_jpConfig->setParams(self::$_jpConfig->_raw);
		}

		if($lang and $iniLang)vmLanguage::initialise();
		self::echoAdmin();
		self::showDebug();
		vmLanguage::debugLangVars();

		self::$_secret = JFactory::getConfig()->get('secret');

		self::$_jpConfig->_params['sctime'] = microtime(TRUE);
		self::$_jpConfig->_params['vmlang'] = self::$vmlang;

		vmTime('time to load config','loadConfig');

		if(!self::$installed){
			//$user = JFactory::getUser();
			//if($user->authorise('core.admin','com_virtuemart') and ($install or $redirected)){
			if(vmAccess::manager('core.admin') and ($install or $redirected)){
				VmConfig::$_jpConfig->set('dangeroustools',1);
			}
			if(!empty($msg)){
				vmLanguage::loadJLang('com_virtuemart_config');
				$app->enqueueMessage(vmText::_($msg), self::$mType);
			}
			if(!empty($link)) $app->redirect($link);
		}


		if($exeTrig and $execTrigger){

			$isSite = VmConfig::isSite();
			self::importVMPlugins('vmuserfield');
			if($isSite){
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('plgVmInitialise', array());
			}

			$execTrigger = false;
		}

		return self::$_jpConfig;
	}

	static function importVMPlugins($ptype){

		 vmSetStartTime('importPlugins');
		 static $types = array('vmextended','vmuserfield', 'vmcalculation', 'vmcustom', 'vmcoupon', 'vmshipment', 'vmpayment');
		 foreach($types as $k => $type){
			 JPluginHelper::importPlugin($type);
			 unset($types[$k]);
			 if($type == $ptype){
				vmTime('time to import plugins '.$ptype,'importPlugins');
			 	break;
			 }
		 }


	}

	/**
	 * Writes the params as string and escape them before
	 * @author Max Milbers
	 */
	function toString(){
		$raw = '';

		foreach(self::$_jpConfig->_params as $paramkey => $value){

			//Texts get broken, when serialized, therefore we do a simple encoding,
			//btw we need serialize for storing arrays   note by Max Milbers
			$raw .= $paramkey.'='.json_encode($value).'|';
		}
		self::$_jpConfig->_raw = substr($raw,0,-1);
		return self::$_jpConfig->_raw;
	}

	/**
	 * Find the configuration value for a given key
	 *
	 * @author Max Milbers
	 * @param string $key Key name to lookup
	 * @return Value for the given key name
	 */
	static function get($key, $default='',$allow_load=FALSE)
	{

		$value = '';
		if ($key) {
			if (empty(self::$_jpConfig->_params) && $allow_load) {
				self::loadConfig();
			}

			if (!empty(self::$_jpConfig->_params) and isset(self::$_jpConfig->_params[$key])) {
					$value = self::$_jpConfig->_params[$key];


			} else {
				$value = $default;
			}

		} else {
			$app = JFactory::getApplication();
			$app -> enqueueMessage('VmConfig get, empty key given');
		}

		return $value;
	}

	static function set($key, $value){

		if (empty(self::$_jpConfig->_params)) {
			self::loadConfig();
		}

		//if($admin = JFactory::getUser()->authorise('core.admin', 'com_virtuemart')){
			if (!empty(self::$_jpConfig->_params)) {
				self::$_jpConfig->_params[$key] = $value;
			}
		//}

	}

	/**
	 * For setting params, needs assoc array
	 * @author Max Milbers
	 */
	function setParams($params){

		$config = explode('|', $params);
		foreach($config as $item){

			$item = explode('=',$item,2);
			if(!empty($item[1])){
				$value = self::parseJsonUnSerialize($item[1],$item[0]);
				if($value!==null){
					$pair[$item[0]] = $value;
				}
			} else {
				$pair[$item[0]] ='';
			}
		}

		self::$_jpConfig->_params = $pair;
	}


	public static function parseJsonUnSerialize($in,$b64Str = false){

		$value = json_decode($in ,$b64Str);
		$ser = false;
		switch(json_last_error()) {
			case JSON_ERROR_DEPTH:
				echo ' - Maximum stack depth exceeded';
				return null;
			case JSON_ERROR_CTRL_CHAR:
				echo ' - Unexpected control character found';
				$ser = true;
				break;
			case JSON_ERROR_SYNTAX:
				//echo ' - Syntax error, malformed JSON';
				$ser = true;
				break;
			case JSON_ERROR_NONE:
				return $value;
		}

		if($ser){
			try {
				if($b64Str and $b64Str==='offline_message' ){
					$value = @unserialize(base64_decode($in) );
				} else {
					$value = @unserialize( $in );
				}
				vmdebug('Error in Json_encode use unserialize ',$in,$value);
				return $value;
			}catch (Exception $e) {
				vmdebug('Exception in loadConfig for unserialize '. $e->getMessage(),$in);
			}
		}
	}


	/**
	 * Find the currently installed version
	 *
	 * @author RickG
	 * @param boolean $includeDevStatus True to include the development status
	 * @return String of the currently installed version
	 */
	static function getInstalledVersion($includeDevStatus=FALSE) {
		return vmVersion::$RELEASE.' '.vmVersion::$CODENAME.' '.vmVersion::$REVISION;
	}

	/**
	 * @deprecated
	 * @return mixed
	 */
	static public function isSuperVendor($uid = 0){
		return vmAccess::isSuperVendor($uid);
	}

	static private $isSite = null;
	static private $siteByApp = null;

	static public function isSite(){

		if(self::$isSite===null){
			$sess = JFactory::getSession();
			$manage = vRequest::getInt('manage',$sess->get('manage', false,'vm'));
			if(!self::isSiteByApp() or ($manage and vmAccess::manager('manage'))){
				self::$isSite = false;
			} else {
				self::$isSite = true;
			}
		}
		return self::$isSite;
	}

	static function isSiteByApp(){
		if(vmDefines::$_appId=='site'){
			self::$siteByApp = true;
		} else {
			self::$siteByApp = false;
		}
		return self::$siteByApp;
	}
}