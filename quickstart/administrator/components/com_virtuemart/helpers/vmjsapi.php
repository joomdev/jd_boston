<?php

defined ('_JEXEC') or die();

/**
 * virtuemart table class, with some additional behaviours.
 *
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

/**
 *
 * Class to provide js API of vm
 * @author Max Milbers
 */
class vmJsApi{

	private static $_jsAdd = array();
	private static $_be = null;

	private function __construct() {

	}



	public static function safe_json_encode($value){
		if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
			$encoded = json_encode($value, JSON_PRETTY_PRINT);
		} else {
			$encoded = json_encode($value);
		}
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = utf8ize($value);
				return safe_json_encode($clean);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()

		}
	}

	function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			if (function_exists ('mb_convert_encoding')) {
				return mb_convert_encoding($mixed, "UTF-8", "auto");
			} else {
				return utf8_encode($mixed);
			}
		}
		return $mixed;
	}

	/**
	 *
	 * @param $name
	 * @param bool $script
	 * @param bool $min
	 * @param bool $defer	http://peter.sh/experiments/asynchronous-and-deferred-javascript-execution-explained/
	 * @param bool $async
	 */
	public static function addJScript($name, $script = false, $defer = false, $async = false, $inline = false, $ver = 0){
		self::$_jsAdd[$name]['script'] = trim($script);
		self::$_jsAdd[$name]['defer'] = $defer;
		self::$_jsAdd[$name]['async'] = $async;
		if(!isset(self::$_jsAdd[$name]['written']))self::$_jsAdd[$name]['written'] = false;
		self::$_jsAdd[$name]['inline'] = $inline;
		self::$_jsAdd[$name]['ver'] = $ver;
	}

	public static function getJScripts(){
		return self::$_jsAdd;
	}

	public static function removeJScript($name){
		unset(self::$_jsAdd[$name]);
	}

	public static function writeJS(){

		$html = '';
		$headInline = '';
		$document = JFactory::getDocument();
		foreach(self::$_jsAdd as $name => &$jsToAdd){

			if($jsToAdd['written']) continue;

			$urlType = 0;
			if(!$jsToAdd['script']){
				$file = $name;
				$cdata = false;
			} else {
				$file = $jsToAdd['script'];
				$cdata = (strpos($file,'//<![CDATA['));
			}

			if($cdata!==false){
				$cdata = true;
				vmdebug('found CDATA '.$name);
			} else {
				if( substr( $file, 0, 1) == "/" ) {
					$urlType = 1;
				}
				if( substr( $file, 0, 2) == "//" or substr( $file, 0, 7) == "http://" or substr( $file, 0, 8) == "https://"){
					$urlType = 2;
				}
			}

			if($jsToAdd['inline'] or !$jsToAdd['script'] or $urlType){



				if(!$urlType and !$jsToAdd['inline']){
					$file = vmJsApi::setPath($file,false,'');
				} else if($urlType === 1){
					$file = JURI::root(true).$file;
				}

				if(empty($file)){
					vmdebug('writeJS javascript with empty file',$name,$jsToAdd);
					continue;
				}

				if($jsToAdd['inline']){
					//$html .= '<script type="text/javascript" src="'.$file .$ver.'"></script>';
					/*$content = file_get_contents(VMPATH_ROOT.$file);
					$html .= '<script type="text/javascript" >'.$content.'</script>';*/
					$script = trim($jsToAdd['script']);
					if(!empty($script)) {
						$script = trim( $script, chr( 13 ) );
						$script = trim( $script, chr( 10 ) );
						$headInline .= $script. chr(13);

						//$document->addScript( $script,"text/javascript",$jsToAdd['defer'],$jsToAdd['async'] );
					}
				} else {
					$ver = '';
					if($jsToAdd['ver']===0){
						$ver = '?vmver='.VM_JS_VER;
					} else if(!empty($jsToAdd['ver'])) {
						$ver = '?vmver='.$jsToAdd['ver'];
					}
					$options = array();
					$attribs = array();
					if($jsToAdd['defer']){
						$attribs['defer'] = 'defer';
					}
					if($jsToAdd['async']){
						$attribs['async'] = 'async';
					}

					$attribs['mime'] = "text/javascript";
					$document->addScript( $file .$ver, $options, $attribs );
				}

			} else {

				$script = trim($jsToAdd['script']);
				if(!empty($script)) {
					$script = trim($script,chr(13));
					$script = trim($script,chr(10));

					$defer = '';
					if($jsToAdd['defer']){
						$defer = 'defer';
					}
					$async = '';
					if($jsToAdd['async']){
						$async = 'async';
					}

					if($cdata===false){
						$html .= '<script '.$defer.' '.$async.' id="'.$name.'-js" type="text/javascript" >//<![CDATA[ '.chr(10).$script.' //]]>'.chr(10).'</script>';
					} else {
						$html .= '<script '.$defer.' '.$async.' id="'.$name.'-js" type="text/javascript" > '.$script.' </script>';
					}
				}

			}
			$html .= chr(13);
			$jsToAdd['written'] = true;
		}
		if(!empty($headInline)){
			$document->addScriptDeclaration( '//<![CDATA[ '.chr(10).$headInline.' //]]>'.chr(10) );
		}
		return $html;
	}

	/**
	 * Write a <script></script> element
	 * @deprecated
	 * @param   string   path to file
	 * @param   string   library name
	 * @param   string   library version
	 * @param   boolean  load minified version
	 * @return  nothing
	 */
	public static function js($namespace, $path=FALSE, $version='', $minified = false) {
		self::addJScript($namespace,false,false);
	}

	/**
	 * Write a <link ></link > element
	 * @param   string   path to file
	 * @param   string   library name
	 * @param   string   library version
	 * @param   boolean   library version
	 * @return  nothing
	 */

	public static function css($namespace, $path = FALSE, $version='', $minified = NULL)
	{

		static $loaded = array();

		// Only load once
		// using of namespace assume same css have same namespace
		// loading 2 time css with this method simply return and do not load it the second time
		if (!empty($loaded[$namespace])) {
			return;
		}

		$file = vmJsApi::setPath( $namespace, $path, $version='', $minified, 'css');

		$document = JFactory::getDocument();
		$document->addStyleSheet($file.'?vmver='.VM_JS_VER);
		$loaded[$namespace] = TRUE;

	}

	public static function loadBECSS (){

		$url = 'administrator/templates/system/css';
		self::css('system',$url);

		$template = VmTemplate::getDefaultTemplate(1);
		$url = 'administrator/templates/'.$template['template'].'/css';
		self::css('template',$url);

	}

	/**
	 * Set file path(look in template if relative path)
	 * @author Patrick
	 */
	public static function setPath( $namespace ,$path = FALSE ,$version='' ,$minified = NULL , $ext = 'js', $absolute_path=false)
	{

		$version = $version ? '.'.$version : '';
		$filemin = $namespace.$version.'.min.'.$ext ;
		$file 	 = $namespace.$version.'.'.$ext ;
		$file_exit_path='';

		$vmStyle = VmTemplate::loadVmTemplateStyle();
		$template = $vmStyle['template'];
		if ($path === FALSE) {

			$uri = VMPATH_THEMES .'/'. $template.'/'.$ext ;
			$path= 'templates/'. $template .'/'.$ext ;
		}

		if (strpos($path, 'templates/'. $template ) !== FALSE){
			// Search in template or fallback
			if (!file_exists($uri.'/'. $file)) {
				$assets_path = VmConfig::get('assets_general_path','components/com_virtuemart/assets/') ;
				$path = str_replace('templates/'. $template.'/',$assets_path, $path);
			}
			$file_exit_path = VMPATH_BASE .'/'.$path;
			if ($absolute_path) {
				$path = VMPATH_BASE .'/'.$path;
			} else {
				$path = JURI::root(TRUE) .'/'.$path;
			}

		}
		elseif ( substr( $path, 0, 2) != "//" )
		{
			if ($absolute_path) {
				$path = VMPATH_BASE .'/'.$path;
			} else {
				$path = JURI::root(TRUE) .'/'.$path;
			}
		}

		//if (VmConfig::get('minified', false) and strpos($path, '//') === false and file_exists($file_exit_path.'/'. $filemin)) $file=$filemin;

		return $path.'/'.$file ;
	}
	/**
	 * Adds jQuery if needed
	 */
	static function jQuery($isSite=-1) {


		if($isSite===-1) $isSite = VmConfig::isSiteByApp();	//Maybe VmConfig::isSite()

		if(VmConfig::get('jquery_framework',true)) JHtml::_('jquery.framework');

		if (!VmConfig::get ('jquery', true) and $isSite) {
			vmdebug('Common jQuery is disabled');
			return FALSE;
		}

		self::jQueryUi();

		self::addJScript( 'jquery.noconflict',false,false,true,false,'');

		self::vmVariables();

		return TRUE;
	}

	static function jQueryUi(){

		if(VmConfig::get('google_jquery', false)){
			self::addJScript('jquery-ui.min', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js', false, false, false, '1.9.2');
		} else {
			self::addJScript('jquery-ui.min', false, false, false, false,'1.9.2');
		}
		self::addJScript('jquery.ui.autocomplete.html', false, false, false, false,'');
	}

	static function vmVariables(){

		static $e = true;
		if($e){
			$v = 'if (typeof Virtuemart === "undefined"){
	var Virtuemart = {};}'."\n";
			$v .= "var vmSiteurl = '".JURI::root()."' ;\n";
			$v .= "Virtuemart.vmSiteurl = vmSiteurl;\n";
			$v .= "var vmLang = '&lang=".VmConfig::$vmlangSef."';\n";
			$v .= "Virtuemart.vmLang = vmLang; \n";
			$v .= "var vmLangTag = '".VmConfig::$vmlangSef."';\n";
			$v .= "Virtuemart.vmLangTag = vmLangTag;\n";
			$itemId = vRequest::getInt('Itemid',false,'GET');
			if(!empty($itemId)){
				$v .= "var Itemid = '&Itemid=".$itemId."';\n";
			} else {
				$v .= 'var Itemid = "";'."\n";
			}
			$v .= 'Virtuemart.addtocart_popup = "'.VmConfig::get('addtocart_popup',1).'"'." ; \n";
			if(VmConfig::get('usefancy',1)) {
				$v .= "var usefancy = true;\n";
			} else {//This is just there for the backward compatibility
				$v .= "var vmCartText = '". addslashes( vmText::_('COM_VIRTUEMART_CART_PRODUCT_ADDED') )."' ;\n" ;
				$v .= "var vmCartError = '". addslashes( vmText::_('COM_VIRTUEMART_MINICART_ERROR_JS') )."' ;\n" ;
				//This is necessary though and should not be removed without rethinking the whole construction
				$v .= "usefancy = false;\n";
			}
			vmJsApi::addJScript('vm-vars',$v,false,true,true);
			$e = false;
		}
	}

	// Virtuemart product and price script
	static function jPrice() {

		if(!VmConfig::get( 'jprice', TRUE ) or !VmConfig::isSite()) {
			return FALSE;
		}
		static $jPrice = false;
		// If exist exit
		if($jPrice) {
			return;
		}
		vmJsApi::jQuery();

		vmLanguage::loadJLang( 'com_virtuemart', true );

		vmJsApi::jSite();


		if(VmConfig::get('addtocart_popup',1)) {
			self::loadPopUpLib();
		}

		vmJsApi::addJScript( 'vmprices',false,false);

		self::vmVariables();
		$onReady = 'jQuery(document).ready(function($) {

		Virtuemart.product($("form.product"));
});';
		vmJsApi::addJScript('ready.vmprices',$onReady);
		$jPrice = TRUE;
		return TRUE;
	}

	static function jSite() {
		if(VmConfig::get ('jsite', TRUE) or !VmConfig::isSite()){
			self::addJScript('vmsite',false,false);
		}
	}

	static function jDynUpdate() {

		self::addJScript('dynupdate',false,false);
		self::addJScript('updDynamicListeners',"
jQuery(document).ready(function() { // GALT: Start listening for dynamic content update.
	// If template is aware of dynamic update and provided a variable let's
	// set-up the event listeners.
	//if (Virtuemart.container)
		Virtuemart.updateDynamicUpdateListeners();

}); ");
	}

	static function JcountryStateList($stateIds, $prefix='', $suffix='_field') {
		static $JcountryStateList = array();
		if (isset($JcountryStateList[$prefix]) or !VmConfig::get ('jsite', TRUE)) {
			return;
		}
		VmJsApi::jSite();

		self::addJScript('vm-countryState'.$prefix,'
		jQuery(document).ready( function($) {
			$("#'.$prefix.'virtuemart_country_id'.$suffix.'").vm2front("list",{dest : "#'.$prefix.'virtuemart_state_id'.$suffix.'",ids : "'.$stateIds.'",prefiks : "'.$prefix.'"});
		});
		');
		$JcountryStateList[$prefix] = TRUE;
		return;
	}

	static function loadPopUpLib(){

		static $done = false;
		if ($done) return true;

		self::vmVariables();

		//I think this should be removed, and replaced by another setting
		if(!VmConfig::get ('jquery', true)) {
			return true;
		}

		if(VmConfig::get('usefancy',1)){
			vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack',false,false,false,false,'1.3.4');
			vmJsApi::css('jquery.fancybox-1.3.4');
		} else {
			vmJsApi::addJScript( 'facebox', false, false, false, false, '' );
			vmJsApi::css( 'facebox' );
		}

		$done = true;
	}

	/**
	 * Creates popup, fancy or other for TOS
	 */
	static function popup($container,$activator){

		static $done = false;
		if ($done) return true;

		self::loadPopUpLib();
		if(VmConfig::get('usefancy',1)) {
			$exeL = "$.fancybox ({ div: '".$container."', content: con });";
		} else {
			$exeL = "$.facebox( { div: '".$container."' }, 'my-groovy-style');";
		}

		$box = "
jQuery(document).ready(function($) {
	$('div".$container."').hide();
	var con = $('div".$container."').html();
	$('a".$activator."').click(function(event) {
		event.preventDefault();
		".$exeL."
	});
});
";
		self::addJScript('box',$box);
		$done = true;

		return;
	}

	static function chosenDropDowns(){
		static $chosenDropDowns = false;

		if(!$chosenDropDowns){

			if(VmConfig::get ('jchosen', 0) or !VmConfig::isSite()){
				vmJsApi::addJScript('chosen.jquery.min',false,false);
				//vmdebug('chosenDropDowns jchosen or not isSite');
				if(VmConfig::isSite()) {
					vmJsApi::addJScript('vmprices');
					$selector = 'jQuery("select.vm-chzn-select")';;
				} else {
					$selector = 'jQuery("select:not(.vm-chzn-add)")';
				}
				vmJsApi::css('chosen');

				$selectText = 'COM_VIRTUEMART_DRDOWN_AVA2ALL';
				$vm2string = "editImage: 'edit image',select_all_text: '".vmText::_('COM_VIRTUEMART_DRDOWN_SELALL')."',select_some_options_text: '".vmText::_($selectText)."'" ;

				$script =
				'if (typeof Virtuemart === "undefined")
	var Virtuemart = {};
	Virtuemart.updateChosenDropdownLayout = function() {
		var vm2string = {'.$vm2string.'};
		'.$selector.'.chosen({enable_select_all: true,select_all_text : vm2string.select_all_text,select_some_options_text:vm2string.select_some_options_text,disable_search_threshold: 5});
		//console.log("updateChosenDropdownLayout");
	}
	jQuery(document).ready( function() {
		Virtuemart.updateChosenDropdownLayout($);
	});
	';

				self::addJScript('updateChosen',$script);
			}
			$chosenDropDowns = true;

		}
		return;
	}

	static function JvalideForm($name='#adminForm')
	{
		static $jvalideForm;
		// If exist exit
		if ($jvalideForm === $name) {
			return;
		}
		self::addJScript('vEngine', "
			jQuery(document).ready(function($) {
				$('".$name."').validationEngine();
			});
"  );
		if ($jvalideForm) {
			return;
		}
		vmJsApi::addJScript( 'jquery.validationEngine');

		$lg = vmLanguage::getLanguage();
		$lang = substr($lg->getTag(), 0, 2);
		$vlePath = vmJsApi::setPath('languages/jquery.validationEngine-'.$lang, FALSE , '' ,$minified = NULL ,   'js', true);
		if(!file_exists($vlePath) or is_dir($vlePath)){
			$lang = 'en';
		}
		vmJsApi::addJScript( 'languages/jquery.validationEngine-'.$lang );

		vmJsApi::css ( 'validationEngine.template' );
		vmJsApi::css ( 'validationEngine.jquery' );
		$jvalideForm = $name;
	}

	static public function vmValidator ($guest=null, $userFields = 0, $prefiks=''){

		if(!isset($guest)){
			$guest = JFactory::getUser()->guest;
		}

		// Implement Joomla's form validation
		JHtml::_('behavior.formvalidator');
		self::vmVariables();

		$regfields = array();
		if(empty($userFields)){
			$regfields = array('username', 'name');
			if($guest){
				$regfields[] = 'password';
				$regfields[] = 'password2';
			}
		} else {
			foreach($userFields as $field){
				if(!empty($field['register'])){
					$regfields[] = $field['name'];
				}
			}
		}

		//vmdebug('vmValidator $regfields',$regfields);
		if(empty($regfields)){
			$jsRegfields = '[]';
		} else {
			$jsRegfields = "['".implode("','",$regfields)."']";
		}

		$js = "Virtuemart.regfields = ".$jsRegfields.";
Virtuemart.prefiks = '".$prefiks."';
Virtuemart.requiredMsg = '" .addslashes (vmText::_ ('COM_VIRTUEMART_MISSING_REQUIRED_JS'))."';

";
		vmJsApi::addJScript('vm-validator',$js);
		vmJsApi::addJScript('vmvalidator');
	}

	// Virtuemart product and price script
	static function jCreditCard()
	{

		static $jCreditCard;
		// If exist exit
		if ($jCreditCard) {
			return;
		}
		vmLanguage::loadJLang('com_virtuemart',true);


		$js = "
		var ccErrors = new Array ()
		ccErrors [0] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_UNKNOWN_TYPE') ). "';
		ccErrors [1] =  '" . addslashes( vmText::_("COM_VIRTUEMART_CREDIT_CARD_NO_NUMBER") ). "';
		ccErrors [2] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_FORMAT')) . "';
		ccErrors [3] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_NUMBER')) . "';
		ccErrors [4] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_WRONG_DIGIT')) . "';
		ccErrors [5] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_EXPIRE_DATE')) . "';
		";

		self::addJScript('creditcard',$js);

		$jCreditCard = TRUE;
		return TRUE;
	}

	/**
	 * ADD some CSS if needed
	 * Prevent duplicate load of CSS stylesheet
	 * @author Max Milbers
	 */
	static function cssSite() {

		static $cssSite;
		if ($cssSite) return;

		// we load one common css and put styles in there
		// that we need and which are can't be covered by bootstrap
		$bootstrapVersion = VmConfig::get('bootstrap', '');
		if ($bootstrapVersion !== '') {
			// Load The Common CSS File
			$cssFile = 'vm-' . $bootstrapVersion . '-common';
			vmJsApi::css($cssFile);

			// Right To Left Support
			if (JFactory::getDocument()->getDirection() == 'rtl') {
				$cssFile = 'vm-' . $bootstrapVersion . '-common-rtl';
				vmJsApi::css($cssFile);
			}
			return FALSE;
		}

		if (!VmConfig::get('css', TRUE)) {
			return FALSE;
		}

		// Get the Page direction for right to left support
		$document = JFactory::getDocument ();
		$direction = $document->getDirection ();
		$cssFile = 'vmsite-' . $direction ;

		$vmStyle = VmTemplate::loadVmTemplateStyle();
		$template = $vmStyle['template'];
		if($template){
			//Fallback for old templates
			$path= 'templates/'.$template.'/css/'. $cssFile.'.css' ;
			if(file_exists($path)){
				// If exist exit
				vmJsApi::css ( $cssFile ) ;
			} else {
//			quorvia allow a combined by hidden cfg and dont load ratings/reviews css if none are used
				$combined = VmConfig::get( 'VMcombinedCssFE', 0 );
				if($combined) {
					$cssFile = 'vm-'.$direction.'-combined-min';
					vmJsApi::css( $cssFile );
				} else {
					$cssFile = 'vm-' . $direction .'-common';
					vmJsApi::css ( $cssFile ) ;

					$cssFile = 'vm-' . $direction .'-site';
					vmJsApi::css ( $cssFile ) ;
					
					if(VmConfig::get( 'showReviewFor', 'none' ) != 'none' and VmConfig::get( 'showRatingFor', 'none' ) !='none') {
						$cssFile = 'vm-'.$direction.'-reviews';
						vmJsApi::css( $cssFile );
					}
				}
			}
			$cssSite = TRUE;
		}

		return TRUE;
	}

	// $yearRange format >> 1980:2010
	// Virtuemart Datepicker script
	static function jDate($date='',$name="date",$id=NULL,$resetBt = TRUE, $yearRange='', $minMax='') {

		if ($yearRange) {
			$yearRange = 'yearRange: "' . $yearRange . '",';
		}

		$test = (int) str_replace(array('-',' ',':'),'',$date);
		if(empty($test)){
			$date = 0;
		}

		if (empty($id)) {
			$id = str_replace(array('[]','[',']','.'),'_',$name);
			$id = trim(str_replace('__','_',$id),'.');
		}

		static $jDate;

		$id = VmHtml::ensureUniqueId($id);
		$dateFormat = vmText::_('COM_VIRTUEMART_DATE_FORMAT_INPUT_J16');//="m/d/y"
		$search  = array('m', 'd', 'Y');
		$replace = array('mm', 'dd', 'yy');
		$jsDateFormat = str_replace($search, $replace, $dateFormat);

		if ($date) {
			$formatedDate = JHtml::_('date', $date, $dateFormat, false );
			/*$date1 = new DateTime($date);
			$formatedDate = $date1->format($dateFormat);*/
		}
		else {
			$formatedDate = vmText::_('COM_VIRTUEMART_NEVER');
		}
		$display  = '<input class="datepicker-db" id="'.$id.'" type="hidden" name="'.$name.'" value="'.$date.'" />';
		$display .= '<input id="'.$id.'_text" class="datepicker" type="text" value="'.$formatedDate.'" />';
		if ($resetBt) {
			$display .= '<span class="vmicon vmicon-16-logout icon-nofloat js-date-reset"></span>';
		}



		self::addJScript('datepicker'.$id,'
		jQuery(document).ready( function($) {
			$(document).on( "focus","#'.$id.'_text", function() {
				$( this ).datepicker({
					changeMonth: true,
					changeYear: true,
					'.$yearRange.'
					'.$minMax.'
					dateFormat:"'.$jsDateFormat.'",
					altField: $(this).prev(),
					altFormat: "yy-mm-dd"
				});
			});
			$(document).on( "click",".js-date-reset", function() {
				$(this).prev("input").val("'.vmText::_('COM_VIRTUEMART_NEVER').'").prev("input").val("0");
			});
		});
		');

		// If exist exit
		if ($jDate) {
			return $display;
		}
		vmJsApi::css('ui/jquery.ui.all');
		$lg = vmLanguage::getLanguage();
		$lang = $lg->getTag();
		$sh_lang = substr($lang, 0, 2);
		$vlePath = vmJsApi::setPath('i18n/jquery.ui.datepicker-'.$lang, FALSE , '' ,$minified = NULL ,   'js', true);
		if(!file_exists($vlePath) or is_dir($vlePath)){
			$vlePath = vmJsApi::setPath('i18n/jquery.ui.datepicker-'.$sh_lang, FALSE , '' ,$minified = NULL ,   'js', true);
			$lang = $sh_lang;
			if(!file_exists($vlePath) or is_dir($vlePath)){
				$lang = 'en-GB';
			}
		}
		vmJsApi::addJScript( 'i18n/jquery.ui.datepicker-'.$lang );

		$jDate = TRUE;
		return $display;
	}


	/*
	 * Convert formated date;
	 * @$date the date to convert
	 * @$format Joomla DATE_FORMAT Key endding eg. 'LC2' for DATE_FORMAT_LC2
	 * @tz Timezone offset, defaults to false, which is the general joomla timezone
	 */

	static function date($date , $format ='LC2', $joomla=FALSE , $tz=false ){

		if (!strcmp ($date, '0000-00-00 00:00:00')) {
			return vmText::_ ('COM_VIRTUEMART_NEVER');
		}
		If ($joomla) {
			$formatedDate = JHtml::_('date', $date, vmText::_('DATE_FORMAT_'.$format),$tz);
		} else {

			$J16 = "_J16";

			$formatedDate = JHtml::_('date', $date, vmText::_('COM_VIRTUEMART_DATE_FORMAT_'.$format.$J16),$tz);
		}
		return $formatedDate;
	}

	static function keepAlive($minlps = 2, $maxlps=5){

		static $done = false;
		if($done) return;
		$done = true;

		$config = JFactory::getConfig();
		$refTime = ($config->get('lifetime') );

		// the longest refresh period is 30 min to prevent integer overflow.
		if ($refTime > 30 || $refTime <= 0) {
			$refTime = 30;
		}

		$url = 'index.php?option=com_virtuemart&view=virtuemart&task=keepalive';
		vmJsApi::addJScript('keepAliveTime','var sessMin = '.$refTime.';var vmAliveUrl = "'.$url.'";var maxlps = "'.$maxlps.'";var minlps = "'.$minlps.'";',false,true,true);
		vmJsApi::addJScript('vmkeepalive',false, true, false);
	}


	static function ajaxCategoryDropDown($id, $param, $emptyOpt){

		vmJsApi::addJScript('ajax_catree');
		$j = "jQuery(document).ready(function($) {
	jQuery(document).ready(function($) {
		Virtuemart.emptyCatOpt = '".$emptyOpt."';
		Virtuemart.param = '".$param."';
		Virtuemart.isAdmin = '".!VmConfig::isSiteByApp()."';
		Virtuemart.loadCategoryTree('".$id."');
	});
});
";
		vmJsApi::addJScript('pro-tech.AjaxCategoriesLoad', $j, false, true, true);
	}

}
