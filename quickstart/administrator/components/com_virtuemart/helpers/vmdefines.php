<?php
/**
 * defines helper class
 *
 * We define here paths and registere classes
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2016-2018 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 */

defined('_JEXEC') or die('Restricted access');

/**
 *
 * We need this extra paths to have always the correct path undependent by loaded application, module or plugin
 * Plugin, module developers must always include this config at start of their application
 *   $vmConfig = VmConfig::loadConfig(); // load the config and create an instance
 *  $vmConfig -> jQuery(); // for use of jQuery
 *  Then always use the defined paths below to ensure future stability
 */

class vmDefines {

	static $_appId = 'site';

	public static function loadJoomlaCms(){


		if (file_exists(VMPATH_ROOT . '/defines.php'))
		{
			include_once VMPATH_ROOT . '/defines.php';
		}

		if (!defined('_JDEFINES'))
		{
			define('JPATH_BASE',VMPATH_BASE);
			require_once JPATH_BASE . '/includes/defines.php';
		}

		require_once JPATH_BASE . '/includes/framework.php';

	}

	static function defines ($appId=0){

		static $incl = false;
		if($incl) return true;
		$incl = true;

		defined('DS') or define('DS', DIRECTORY_SEPARATOR);

		if(defined('JVERSION')){	//We are in joomla
			defined ('VMPATH_ROOT') or define ('VMPATH_ROOT', JPATH_ROOT);
			defined('JVM_VERSION') or define ('JVM_VERSION', 3);
			defined('VM_USE_BOOTSTRAP') or define ('VM_USE_BOOTSTRAP', 1);
			$vmPathLibraries = JPATH_PLATFORM;

			defined('WP_VERSION') or define ('WP_VERSION', 0);
		} else {
			defined ('JVM_VERSION') or define ('JVM_VERSION', 0);

			//Todo ???? need to be checked
			!defined ('WPINC') or define ('WP_VERSION', get_bloginfo('version'));

			//defined ('VMPATH_ROOT') or define ('VMPATH_ROOT', dirname( __FILE__ ));

			//defined('_JEXEC') or define('_JEXEC', 1);
			$vmPathLibraries = VMPATH_ROOT .'/libraries';

		}

		if($appId===0){
			if(defined('JVERSION')){
				$appId = JFactory::getApplication()->getName();
			} else {
				$appId = 'site';
			}
		}

		defined ('VMPATH_LIBS') or define ('VMPATH_LIBS', $vmPathLibraries);

		defined ('VMPATH_ADMINISTRATOR') or define ('VMPATH_ADMINISTRATOR',	VMPATH_ROOT .'/administrator');
		defined ('VMPATH_ADMIN') or define ('VMPATH_ADMIN', VMPATH_ADMINISTRATOR .'/components/com_virtuemart' );

		defined('VM_VERSION') or define ('VM_VERSION', 3);

		self::$_appId = $appId;

		$admin = '';
		if($appId == 'administrator'){
			$admin = '/administrator';//echo('in administrator');
		}
		defined ('VMPATH_BASE') or define ('VMPATH_BASE',VMPATH_ROOT.$admin);
		defined ('VMPATH_THEMES') or define ('VMPATH_THEMES', VMPATH_ROOT.$admin.'/templates' );
		defined ('VMPATH_COMPONENT') or define( 'VMPATH_COMPONENT', VMPATH_BASE .'/components/com_virtuemart' );

		//vmSetStartTime('includefiles');

		defined ('VM_USE_BOOTSTRAP') or define ('VM_USE_BOOTSTRAP', 0);
		defined ('VMPATH_SITE') or define ('VMPATH_SITE', VMPATH_ROOT .'/components/com_virtuemart' );

		defined ('VMPATH_PLUGINLIBS') or define ('VMPATH_PLUGINLIBS', VMPATH_ADMIN .'/plugins');
		defined ('VMPATH_PLUGINS') or define ('VMPATH_PLUGINS', VMPATH_ROOT .'/plugins' );
		defined ('VMPATH_MODULES') or define ('VMPATH_MODULES', VMPATH_ROOT .'/modules' );


//legacy
		defined ('JPATH_VM_SITE') or define('JPATH_VM_SITE', VMPATH_SITE );
		defined ('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', VMPATH_ADMIN);
// define( 'VMPATH_ADMIN', JPATH_ROOT.'/administrator'.'/components'.'/com_virtuemart' );
		defined('JPATH_VM_PLUGINS') or define( 'JPATH_VM_PLUGINS', VMPATH_PLUGINLIBS );
		defined('JPATH_VM_MODULES') or define( 'JPATH_VM_MODULES', VMPATH_MODULES );

		//This number is for obstruction, similar to the prefix jos_ of joomla it should be avoided
//to use the standard 7, choose something else between 1 and 99, it is added to the ordernumber as counter
// and must not be lowered.
		defined('VM_ORDER_OFFSET') or define('VM_ORDER_OFFSET',3);


		self::core();

		defined('VM_REV') or define('VM_REV',vmVersion::$REVISION);
		$v = hash('crc32b',(VMPATH_ROOT.VM_REV));
		defined('VM_JS_VER') or define('VM_JS_VER', $v);

		if(!defined('JVERSION')){
			self::loadJoomlaCms();
		}

		/*		if(!interface_exists('vIObject'))
					require(VMPATH_ADMIN .'/vmf/vinterfaces.php');
				if(!class_exists('vObject')) require(VMPATH_ADMIN .'/vmf/vobject.php');

				if(!class_exists('vBasicModel'))
					require(VMPATH_ADMIN .'/vmf/vbasicmodel.php');

				if(!class_exists('vController')) require(VMPATH_ADMIN .'/vmf/vcontroller.php');
		*/
		//if(!class_exists('VmTable')){
		//require(VMPATH_ADMIN .'/helpers/vmtable.php');
		VmTable::addIncludePath(VMPATH_ADMIN .'/tables','Table');
		//}

		//if(!class_exists('VmModel')) require(VMPATH_ADMIN .'/helpers/vmmodel.php');
//		if(!class_exists('vUri')) require(VMPATH_ADMIN .'/vmf/environment/uri.php');

		//if(!class_exists('vHtml')) require(VMPATH_ADMIN .'/vmf/html/html.php');
		//if(!class_exists('vmJsApi')) require(VMPATH_ADMIN .'/helpers/vmjsapi.php');

		/*		if(!class_exists('vDispatcher')) require(VMPATH_ADMIN .'/vmf/dispatcher.php');
				if(!class_exists('vPlugin')) require(VMPATH_ADMIN .'/vmf/plugin/plugin.php');
				if(!class_exists('vUser')) require(VMPATH_ADMIN .'/vmf/user/user.php');
				//vmTime('Time to create Config', 'includefiles');
		*/
		//Force Joomla to use the FE overrides
		//defined('JPATH_SITE') or define('JPATH_SITE','VMPATH_SITE');
	}

	static public function core($rootPath = VMPATH_ROOT){

		$vmpath_admin = $rootPath.'/administrator/components/com_virtuemart';
		$vmpath_pluginlibs = $vmpath_admin.'/plugins';
		$vmpath_site = $rootPath.'/components/com_virtuemart';
		//if(!class_exists('JFile')) require(VMPATH_LIBS.DS.'joomla'.DS.'filesystem'.DS.'file.php');
		JLoader::register('JFile', VMPATH_LIBS.'/joomla/filesystem/file.php');
		JLoader::register('JFolder', VMPATH_LIBS.'/joomla/filesystem/folder.php');
		//JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');

		JLoader::register('vmVersion', $vmpath_admin.'/version.php');
		JLoader::register('AdminUIHelper', $vmpath_admin.'/helpers/adminui.php');
		JLoader::register('calculationHelper', $vmpath_admin.'/helpers/calculationh.php');
		JLoader::register('VmConnector', $vmpath_admin.'/helpers/connection.php');
		JLoader::register('Creditcard', $vmpath_admin.'/helpers/creditcard.php');
		JLoader::register('CurrencyDisplay', $vmpath_admin.'/helpers/currencydisplay.php');
		JLoader::register('VmHtml', $vmpath_admin.'/helpers/html.php');
		JLoader::register('VmImage', $vmpath_admin.'/helpers/image.php');
		JLoader::register('Img2Thumb', $vmpath_admin.'/helpers/img2thumb.php');
		JLoader::register('VmMediaHandler', $vmpath_admin.'/helpers/mediahandler.php');
		JLoader::register('vmFile', $vmpath_admin.'/helpers/mediahandler.php');
		JLoader::register('Migrator', $vmpath_admin.'/helpers/migrator.php');
		JLoader::register('ShopFunctions', $vmpath_admin.'/helpers/shopfunctions.php');
		JLoader::register('GenericTableUpdater', $vmpath_admin.'/helpers/tableupdater.php');
		JLoader::register('vmAccess', $vmpath_admin.'/helpers/vmaccess.php');
		JLoader::register('VmController', $vmpath_admin.'/helpers/vmcontroller.php');
		JLoader::register('vmCrypt', $vmpath_admin.'/helpers/vmcrypt.php');
		//JLoader::register('vmFilter', $vmpath_admin.'/helpers/vmfilter.php');
		JLoader::register('vmJsApi', $vmpath_admin.'/helpers/vmjsapi.php');
		JLoader::register('vmLanguage', $vmpath_admin.'/helpers/vmlanguage.php');
		JLoader::register('VmModel', $vmpath_admin.'/helpers/vmmodel.php');
		JLoader::register('VmPagination', $vmpath_admin.'/helpers/vmpagination.php');
		JLoader::register('vmRSS', $vmpath_admin.'/helpers/vmrss.php');
		JLoader::register('VmTable', $vmpath_admin.'/helpers/vmtable.php');
		JLoader::register('VmTableData', $vmpath_admin.'/helpers/vmtabledata.php');
		JLoader::register('VmTableXarray', $vmpath_admin.'/helpers/vmtablexarray.php');
		JLoader::register('vmText', $vmpath_admin.'/helpers/vmtext.php');
		JLoader::register('vmUploader', $vmpath_admin.'/helpers/vmuploader.php');
		JLoader::register('vmURI', $vmpath_admin.'/helpers/vmuri.php');
		JLoader::register('VmViewAdmin', $vmpath_admin.'/helpers/vmviewadmin.php');
		JLoader::register('vObject', $vmpath_admin.'/helpers/vobject.php');
		JLoader::register('vRequest', $vmpath_admin.'/helpers/vrequest.php');

		JLoader::register('VirtueMartModelCalc', $vmpath_admin.'/models/calc.php');
		JLoader::register('VirtueMartModelCategory', $vmpath_admin.'/models/category.php');
		JLoader::register('VirtueMartModelConfig', $vmpath_admin.'/models/config.php');
		JLoader::register('VirtueMartModelCountry', $vmpath_admin.'/models/country.php');
		JLoader::register('VirtueMartModelCoupon', $vmpath_admin.'/models/coupon.php');
		JLoader::register('VirtueMartModelCurrency', $vmpath_admin.'/models/currency.php');
		JLoader::register('VirtueMartModelCustom', $vmpath_admin.'/models/custom.php');
		JLoader::register('VirtueMartModelCustomfields', $vmpath_admin.'/models/customfields.php');
		JLoader::register('VirtueMartModelInventory', $vmpath_admin.'/models/inventory.php');
		JLoader::register('VirtueMartModelInvoice', $vmpath_admin.'/models/invoice.php');
		JLoader::register('VirtueMartModelManufacturer', $vmpath_admin.'/models/manufacturer.php');
		JLoader::register('VirtuemartModelManufacturercategories', $vmpath_admin.'/models/manufacturercategories.php');
		JLoader::register('VirtueMartModelMedia', $vmpath_admin.'/models/media.php');
		JLoader::register('VirtueMartModelOrders', $vmpath_admin.'/models/orders.php');
		JLoader::register('VirtueMartModelOrderstatus', $vmpath_admin.'/models/orderstatus.php');
		JLoader::register('VirtueMartModelPaymentmethod', $vmpath_admin.'/models/paymentmethod.php');
		JLoader::register('VirtueMartModelProduct', $vmpath_admin.'/models/product.php');
		JLoader::register('VirtueMartModelRatings', $vmpath_admin.'/models/ratings.php');
		JLoader::register('VirtuemartModelReport', $vmpath_admin.'/models/report.php');
		JLoader::register('VirtueMartModelShipmentmethod', $vmpath_admin.'/models/shipmentmethod.php');
		JLoader::register('VirtueMartModelShopperGroup', $vmpath_admin.'/models/shoppergroup.php');
		JLoader::register('VirtueMartModelUpdatesMigration', $vmpath_admin.'/models/updatesmigration.php');
		JLoader::register('VirtueMartModelState', $vmpath_admin.'/models/state.php');
		JLoader::register('VirtueMartModelUser', $vmpath_admin.'/models/user.php');
		JLoader::register('VirtueMartModelUserfields', $vmpath_admin.'/models/userfields.php');
		JLoader::register('VirtueMartModelVendor', $vmpath_admin.'/models/vendor.php');

		JLoader::register('vmCalculationPlugin', $vmpath_pluginlibs.'/vmcalculationplugin.php');
		JLoader::register('vmCouponPlugin', $vmpath_pluginlibs.'/vmcouponplugin.php');
		JLoader::register('vmCurrencyPlugin', $vmpath_pluginlibs.'/vmcurrencyplugin.php');
		JLoader::register('vmCustomPlugin', $vmpath_pluginlibs.'/vmcustomplugin.php');
		JLoader::register('vmExtendedPlugin', $vmpath_pluginlibs.'/vmextendedplugin.php');
		JLoader::register('vmPlugin', $vmpath_pluginlibs.'/vmplugin.php');
		JLoader::register('vmPSPlugin', $vmpath_pluginlibs.'/vmpsplugin.php');
		JLoader::register('vmShopperPlugin', $vmpath_pluginlibs.'/vmshopperplugin.php');
		JLoader::register('vmUserfieldPlugin', $vmpath_pluginlibs.'/vmuserfieldtypeplugin.php');

		JLoader::register('TableCalcs', $vmpath_admin.'/tables/calcs.php');
		JLoader::register('TableCategories', $vmpath_admin.'/tables/categories.php');
		JLoader::register('TableCategory_medias', $vmpath_admin.'/tables/category_medias.php');
		JLoader::register('TableManufacturers', $vmpath_admin.'/tables/manufacturers.php');
		JLoader::register('TableMedias', $vmpath_admin.'/tables/medias.php');
		JLoader::register('TableUserinfos', $vmpath_admin.'/tables/userinfos.php');
		JLoader::register('TableVendors', $vmpath_admin.'/tables/TableVendors.php');


		JLoader::register('VirtuemartViewConfig', $vmpath_admin.'/views/config/view.html.php');

		//JLoader::register('vFactory', VMPATH_ADMIN.'/vmf/vfactory.php');

		//FE
		//JLoader::register('VirtueMartControllerVirtuemart',VMPATH_SITE .'/controllers/virtuemart.php');
		JLoader::register('VirtueMartControllerInvoice',$vmpath_site .'/controllers/invoice.php');
		JLoader::register('VirtueMartCart', $vmpath_site.'/helpers/cart.php');
		JLoader::register('CouponHelper', $vmpath_site.'/helpers/coupon.php');
		JLoader::register('shopFunctionsF', $vmpath_site.'/helpers/shopfunctionsf.php');
		JLoader::register('VmPdf', $vmpath_site.'/helpers/vmpdf.php');
		JLoader::register('VmVendorPDF', $vmpath_site.'/helpers/vmpdf.php');
		JLoader::register('VmTemplate', $vmpath_site.'/helpers/vmtemplate.php');
		JLoader::register('VmView', $vmpath_site.'/helpers/vmview.php');
		//JLoader::register('VirtuemartViewUser', $vmpath_site.'/views/user/view.html.php'); We must not register views which exists in FE and BE (could be done with "use")
		JLoader::register('VirtuemartViewInvoice', $vmpath_site.'/views/invoice/view.html.php');
	}

	static public function tcpdf(){

		static $tcPath = null;
		if($tcPath === null){
			$paths = array('/vendor/tecnickcom/tcpdf', '/tcpdf');
			foreach($paths as $p){
				if(file_exists(VMPATH_LIBS .$p.'/tcpdf.php')){
					$tcPath = $p;
					break;
				}
			}
			if($tcPath === null){
				vmLanguage::loadJLang('com_virtuemart_config');
				vmWarn('COM_VIRTUEMART_TCPDF_NINSTALLED');
				$tcPath = false;
			} else {
				defined ('VMPATH_TCPDF') or define ('VMPATH_TCPDF', VMPATH_LIBS .$tcPath );
				JLoader::register('TCPDF',VMPATH_TCPDF .'/tcpdf.php');
			}
		}
		return $tcPath;
	}
}