<?php
defined('_JEXEC') or die('');
/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 *
 * This class provides the functions for the calculatoins
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2011 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
// Load the view framework
jimport( 'joomla.application.component.view');
// Load default helpers

class VmView extends JViewLegacy{

	var $isMail = false;
	var $isPdf = false;
	var $writeJs = true;
	var $useSSL = 0;

	public function display($tpl = null) {

		if($this->isMail or $this->isPdf){
			$this->writeJs = false;
		}
		$this->useSSL = vmURI::useSSL();

		$bs = VmConfig::get('bootstrap','');
		if($bs!==''){
			$l = $this->getLayout();
			$this->setLayout($bs.'-'.$l);
			vmdebug('my layout here ',$bs.$l);
		}

		if(!VmConfig::get('useLayoutOverrides',1)){
			//we just add the default again, so it is first in queque
			$this->addTemplatePath(VMPATH_ROOT .'/components/com_virtuemart/views/'.$this->_name.'/tmpl');
		}

		$result = $this->loadTemplate($tpl);
		if ($result instanceof Exception) {
			return $result;
		}

		echo $result;
		if($this->writeJs){
			self::withKeepAlive();
			if(get_class($this)!='VirtueMartViewProductdetails'){
				echo vmJsApi::writeJS();
			}
		}

	}

	public function withKeepAlive(){

		$cart = VirtueMartCart::getCart();
		if(!empty($cart->cartProductsData)){
			vmJsApi::keepAlive(1,4);
		}
	}

	/**
	 * Renders sublayouts
	 *
	 * @author Max Milbers
	 * @param $name
	 * @param int $viewData viewdata for the rendered sublayout, do not remove
	 * @return string
	 */
	public function renderVmSubLayout($name=0,$viewData=0){

		if ($name === 0) {
			$name = $this->_name;
		}

		$lPath = self::getVmSubLayoutPath ($name);

		if($lPath){
			if($viewData!==0 and is_array($viewData)){
				foreach($viewData as $k => $v){
					if ('_' != substr($k, 0, 1) and !isset($this->{$k})) {
						$this->{$k} = $v;
					}
				}
			}
			ob_start ();
			include ($lPath);
			return ob_get_clean();
		} else {
			vmdebug('renderVmSubLayout layout not found '.$name);
		}

	}

	static public function getVmSubLayoutPath($name){

		static $layouts = array();

		if(isset($layouts[$name])){
			return $layouts[$name];
		} else {
			$vmStyle = VmTemplate::loadVmTemplateStyle();
			$template = $vmStyle['template'];

			// get the template and default paths for the layout if the site template has a layout override, use it
			$tP = VMPATH_ROOT .'/templates/'. $template .'/html/com_virtuemart/sublayouts/'. $name .'.php';
			$nP = VMPATH_SITE .'/sublayouts/'. $name . '.php';

			if (VmConfig::get('useLayoutOverrides',1) and JFile::exists ($tP)) {
				$layouts[$name] = $tP;
			} else if (JFile::exists ($nP)) {
				$layouts[$name] = $nP;
			} else {
				$layouts[$name] = false;
			}

			return $layouts[$name];
		}


	}

	function prepareContinueLink($product=false){

		$virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId ();
		$categoryStr = '';

		if (empty($virtuemart_category_id) and $product) {
			$virtuemart_category_id = $product->canonCatId;
			vmdebug('Using product canon cat ',$virtuemart_category_id);
		}

		if ($virtuemart_category_id) {
			$categoryStr = '&virtuemart_category_id=' . $virtuemart_category_id;
		}

		$ItemidStr = '';
		$Itemid = shopFunctionsF::getLastVisitedItemId();
		if(!empty($Itemid)){
			$ItemidStr = '&Itemid='.$Itemid;
		}

		if(VmConfig::get('sef_for_cart_links', false)){
			$this->useSSL = vmURI::useSSL();
			$this->continue_link = JRoute::_('index.php?option=com_virtuemart&view=category' . $categoryStr.$ItemidStr);
			$this->cart_link = JRoute::_('index.php?option=com_virtuemart&view=cart',false,$this->useSSL);
		} else {
			$lang = '';
			if(VmLanguage::$jLangCount>1 and !empty(VmConfig::$vmlangSef)){
				$lang = '&lang='.VmConfig::$vmlangSef;
			}

			$this->continue_link = JURI::root() .'index.php?option=com_virtuemart&view=category' . $categoryStr.$lang.$ItemidStr;

			$juri = JUri::getInstance();
			$uri = $juri->toString(array( 'host', 'port'));

			$scheme = $juri->toString(array( 'scheme'));
			$scheme = substr($scheme,0,-3);
			if($scheme!='https' and $this->useSSL){
				$scheme .='s';
			}
			$this->cart_link = $scheme.'://'.$uri. JURI::root(true).'/index.php?option=com_virtuemart&view=cart'.$lang;
		}

		$this->continue_link_html = '<a class="continue_link" href="' . $this->continue_link . '">' . vmText::_ ('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';



		return;
	}

	function linkIcon($link,$altText ='',$boutonName,$verifyConfigValue=false, $modal = true, $use_icon=true,$use_text=false,$class = ''){
		if ($verifyConfigValue) {
			if ( !VmConfig::get($verifyConfigValue, 0) ) return '';
		}
		$folder = 'media/system/images/'; //shouldn't be root slash before media, as it automatically tells to look in root directory, for media/system/ which is wrong it should append to root directory.
		$text='';
		if ( $use_icon ) $text .= JHtml::_('image', $folder.$boutonName.'.png',  vmText::_($altText), null, false, false); //$folder shouldn't be as alt text, here it is: image(string $file, string $alt, mixed $attribs = null, boolean $relative = false, mixed $path_rel = false) : string, you should change first false to true if images are in templates media folder
		if ( $use_text ) $text .= '&nbsp;'. vmText::_($altText);
		if ( $text=='' )  $text .= '&nbsp;'. vmText::_($altText);
		if ($modal) return '<a '.$class.' class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 550}}" title="'. vmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
		else 		return '<a '.$class.' title="'. vmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
	}

	public function escape($var)
	{
		if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities')))
		{
			$result = call_user_func($this->_escape, $var, ENT_COMPAT, $this->_charset);
		} else {
			$result =  call_user_func($this->_escape, $var);
		}

		return $result;
	}

}