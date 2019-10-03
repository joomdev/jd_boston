<?php
defined ('_JEXEC') or die('Restricted access');

/**
 *
 * VirtueMart script file
 *
 * This file is executed during install/upgrade and uninstall
 *
 * @author Max Milbers, Valérie Isaksen
 * @package VirtueMart
 */







class com_virtuemart_allinoneInstallerScript {

	static $html = '';

	public function preflight () {
		//Update Tables
		if (!class_exists ('VmConfig')) {
			if(file_exists(JPATH_ADMINISTRATOR .'/components/com_virtuemart/helpers/config.php')){
				require(JPATH_ADMINISTRATOR .'/components/com_virtuemart/helpers/config.php');
			} else {

				JFactory::getApplication()->enqueueMessage('Install the VirtueMart Core first ');
				return false;
			}
		}
		VmConfig::loadConfig();
		if(!method_exists('vRequest','vmSpecialChars')){
			JFactory::getApplication()->enqueueMessage('Update the VirtueMart Core first ');
			return false;
		}
		VmConfig::ensureMemoryLimit(128);
		VmConfig::ensureExecutionTime(120);
	}

	public function getVMPathRoot(){
		$source_path = dirname(__FILE__);
		if(empty($source_path)){
			$source_path = JPATH_ROOT;
		}
		return $source_path;
	}

	public function install () {
		//$this->vmInstall();
	}

	public function discover_install () {
		$this->vmInstall();
	}

	public function postflight () {

		$this->vmInstall ();
	}

	public function vmInstall ($dontMove=0) {

		jimport ('joomla.installer.installer');

		if(!class_exists('JFile')) require(VMPATH_LIBS .'/joomla/filesystem/file.php');
		if(!class_exists('JFolder')) require(VMPATH_LIBS .'/joomla/filesystem/folder.php');

		vmLanguage::loadJLang('com_virtuemart');
		$this->createIndexFolder (JPATH_ROOT .'/plugins/vmcalculation');
		$this->createIndexFolder (JPATH_ROOT .'/plugins/vmcustom');
		$this->createIndexFolder (JPATH_ROOT .'/plugins/vmpayment');
		$this->createIndexFolder (JPATH_ROOT .'/plugins/vmshipment');
		$this->createIndexFolder (JPATH_ROOT .'/plugins/vmuserfield');

		if(empty($dontMove)){
			$this->path = $this->getVMPathRoot();
		} else {
			$this->path = JPATH_ROOT;
		}
		$this->dontMove = $dontMove;
		self::$html .= '<a
				href="http://virtuemart.net"
				target="_blank"> <img
					border="0"
					align="left" style="margin-right: 20px"
					src="components/com_virtuemart/assets/images/vm_menulogo.png"
					alt="Cart" /> </a>';
		self::$html .= '<h3 style="clear: both;">Installing VirtueMart Plugins and Modules</h3>';
		self::$html .= "<p>The AIO component (com_virtuemart_aio) is used to install or update all the plugins and modules essential to VirtueMart in one go.</p>";
		self::$html .= "<p>Do not uninstall it.</p>";


		//We do this dirty here, is just the finish page for installation, we must know if we are allowed to add sample data
		$db = JFactory::getDbo();
		$q = 'SELECT count(*) FROM `#__virtuemart_products` WHERE `virtuemart_product_id`!="0" ';
		$db->setQuery($q);
		$productsExists = $db->loadResult();
		if (!$productsExists) {
			$file = 'components/com_virtuemart/assets/css/toolbar_images.css';
			$document = JFactory::getDocument();
			$document->addStyleSheet($file.'?vmver='.VM_REV);
			if (JVM_VERSION < 3) {
				$class="button";
			} else {
				$class="btn btn-primary";
			}
			self::$html .= '<p><strong>
					'.JText::_('COM_VIRTUEMART_INSTALL_SAMPLE_DATA_OPTION') . ' ' . JText::_('COM_VIRTUEMART_INSTALL_SAMPLE_DATA').'
				</strong>
				'.JText::_('COM_VIRTUEMART_INSTALL_SAMPLE_DATA_TIP').'
			</p>
			<div id="cpanel">
				<div class="icon">
					<a class="'.$class.'" style="padding:12px;font-size:20px"
					   href="'.JROUTE::_('index.php?option=com_virtuemart&view=updatesmigration&task=installSampleData&' . JSession::getFormToken() . '=1') .'">
						'.JText::_('COM_VIRTUEMART_INSTALL_SAMPLE_DATA').'
					</a>
					<span class="vmicon48"></span>
				</div>
			</div>
			<div style="clear: both;"></div>';
		}

		self::$html .= '<table><tr><th>Plugins</th><td></td></tr>';


		$this->installPlugin ('VM Payment - Standard', 'plugin', 'standard', 'vmpayment',1);
		$this->installPlugin ('VM Payment - PayPal', 'plugin', 'paypal', 'vmpayment');
		$this->installPlugin ('VM Payment - Sofort Banking/Überweisung', 'plugin', 'sofort', 'vmpayment');
		$this->installPlugin ('VM Payment - Skrill', 'plugin', 'skrill', 'vmpayment');
		$this->installPlugin ('VM Payment - Klarna', 'plugin', 'klarna', 'vmpayment');
		$this->installPlugin ('VM Payment - KlarnaCheckout', 'plugin', 'klarnacheckout', 'vmpayment');


		$this->installPlugin ('VM Payment - Heidelpay', 'plugin', 'heidelpay', 'vmpayment');
		$this->installPlugin ('VM Payment - Paybox', 'plugin', 'paybox', 'vmpayment');

		$this->installPlugin ('VM Payment - 2Checkout', 'plugin', 'tco', 'vmpayment');
		$this->installPlugin ('VM Payment - eWay', 'plugin', 'eway', 'vmpayment');

		//$this->installPlugin ('VM Payment - Pay with Amazon', 'plugin', 'amazon', 'vmpayment');
		//$this->installPlugin ('System - Pay with Amazon', 'plugin', 'amazon', 'system');

		$this->installPlugin ('VM Payment - Realex HPP & API', 'plugin', 'realex_hpp_api', 'vmpayment');
		$this->installPlugin ('VM UserField - Realex HPP & API', 'plugin', 'realex_hpp_api', 'vmuserfield');



		$this->installPlugin ('VM Payment - Authorize.net', 'plugin', 'authorizenet', 'vmpayment');

		$this->installPlugin ('VM Payment - Sofort iDeal', 'plugin', 'sofort_ideal', 'vmpayment');
		$this->installPlugin ('VM Payment - Klikandpay', 'plugin', 'klikandpay', 'vmpayment');

		$this->installPlugin ('VM Shipment - By weight, ZIP and countries', 'plugin', 'weight_countries', 'vmshipment', 1);

		$this->installPlugin ('VM Custom - Customer text input', 'plugin', 'textinput', 'vmcustom', 1);
		$this->installPlugin ('VM Custom - Product specification', 'plugin', 'specification', 'vmcustom', 1);
		$this->installPlugin ('VM Custom - iStraxx Download simple', 'plugin', 'istraxx_download_simple', 'vmcustom', 1);

		//$this->installPlugin ('VM Custom - Stockable variants', 'plugin', 'stockable', 'vmcustom', 1);
		$this->installPlugin ('VM Calculation - Avalara Tax', 'plugin', 'avalara', 'vmcalculation' );

		// 			$table = '#__virtuemart_customs';
		// 			$fieldname = 'field_type';
		// 			$fieldvalue = 'G';
		// 			$this->addToRequired($table,$fieldname,$fieldvalue,"INSERT INTO `#__virtuemart_customs`
		// 					(`custom_parent_id`, `admin_only`, `custom_title`, `custom_tip`, `custom_value`, `custom_field_desc`,
		// 					 `field_type`, `is_list`, `is_hidden`, `is_cart_attribute`, `published`) VALUES
		// 						(0, 0, 'COM_VIRTUEMART_STOCKABLE_PRODUCT', 'COM_VIRTUEMART_STOCKABLE_PRODUCT_TIP', NULL,
		// 					'COM_VIRTUEMART_STOCKABLE_PRODUCT_DESC', 'G', 0, 0, 0, 1 );");

		$this->installPlugin ('VirtueMart Product', 'plugin', 'virtuemart', 'search');
		$this->updateMoneyBookersToSkrill();

		$this->installPlugin ('VM Framework Loader during Plugin Updates', 'plugin', 'vmLoaderPluginUpdate', 'system', 1);

		$this->updateShipmentWeight_countries_keys();


		$task = vRequest::getCmd ('task');
		if ($task != 'updateDatabase') {
			self::$html .= "<tr><th>Modules</th><td></td></tr>";
			// modules auto move
			$src = $this->path .'/modulesBE';
			$dst = JPATH_ROOT .'/administrator/modules';
			$this->recurse_copy ($src, $dst);
			$alreadyInstalled = $this->VmModulesAlreadyInstalled();
			//echo "Checking VirtueMart modules...";
				$defaultParams = '{"show_vmmenu":"1"}';
				$this->installModule ('VM - Administrator Module', 'mod_vmmenu', 5, $defaultParams, $dst,1,'menu',3,$alreadyInstalled);



			// modules auto move
			$src = $this->path .'/modules';
			$dst = JPATH_ROOT .'/modules';
			$this->recurse_copy ($src, $dst);
			$alreadyInstalled = $this->VmModulesAlreadyInstalled();

			$defaultParams = '{"text_before":"","product_currency":"","cache":"1","moduleclass_sfx":"","class_sfx":""}';
			$this->installModule ('VM - Currencies Selector', 'mod_virtuemart_currencies', 5, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);


			$defaultParams = '{"product_group":"featured","max_items":"1","products_per_row":"1","display_style":"list","show_price":"1","show_addtocart":"1","headerText":"Best products","footerText":"","filter_category":"0","virtuemart_category_id":"0","cache":"0","moduleclass_sfx":"","class_sfx":""}';
			$this->installModule ('VM - Featured products', 'mod_virtuemart_product', 3, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);


			$defaultParams = '{"product_group":"topten","max_items":"1","products_per_row":"1","display_style":"list","show_price":"1","show_addtocart":"1","headerText":"","footerText":"","filter_category":"0","virtuemart_category_id":"0","cache":"0","moduleclass_sfx":"","class_sfx":""}';
			$this->installModule ('VM - Best Sales', 'mod_virtuemart_product', 1, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);

			$defaultParams = '{"width":"20","text":"","button":"","button_pos":"right","imagebutton":"","button_text":""}';
			$this->installModule ('VM - Search in Shop', 'mod_virtuemart_search', 2, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);

			$defaultParams = '{"show":"all","display_style":"list","manufacturers_per_row":"1","headerText":"","footerText":""}';
			$this->installModule ('VM - Manufacturer', 'mod_virtuemart_manufacturer', 8, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);

			$defaultParams = '{"moduleclass_sfx":"","show_price":"1","show_product_list":"1"}';
			$this->installModule ('VM - Shopping cart', 'mod_virtuemart_cart', 0, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);

			$defaultParams = '{"Parent_Category_id":"0","layout":"default","cache":"0","moduleclass_sfx":"","class_sfx":""}';
			$this->installModule ('VM - Category', 'mod_virtuemart_category', 4, $defaultParams, $dst, 0,'position-4',1,$alreadyInstalled);

			// libraries auto move
			$src = $this->path .'/libraries';
			$dst = JPATH_ROOT .'/libraries';
			$this->recurse_copy ($src, $dst);
			self::$html .= "<tr><th>libraries moved to the joomla libraries folder</th><td></td></tr>";

			self::$html .= "</table>";


		} else {
			self::$html .= "<h3>Updated VirtueMart Plugin tables</h3>";
		}

		$dst = JPATH_ROOT .'/administrator/modules';
		$umimodel = VmModel::getModel('updatesmigration');//$model = new VirtueMartModelUpdatesMigration();
		$umimodel->updateJoomlaUpdateServer( 'module', 'mod_vmmenu', $dst   );

		$modules = array(
		'mod_virtuemart_currencies',
		'mod_virtuemart_product',
		'mod_virtuemart_search',
		'mod_virtuemart_manufacturer',
		'mod_virtuemart_cart',
		'mod_virtuemart_category'
		);
		$dst = JPATH_ROOT .'/modules';
		foreach ($modules as $module) {
			$umimodel->updateJoomlaUpdateServer( 'module', $module, $dst   );
		}

		$this->updateOrderingExtensions();

		$this->replaceStockableByDynamicChilds();
		self::$html .= "<h3>Installation Successful.</h3>";

		echo self::$html;
		vRequest::setVar('aio_html',self::$html);
		return TRUE;

	}

	/**
	 * Replaces the old stockable plugin by the native method of vm
	 */
	public function replaceStockableByDynamicChilds(){
		$db = JFactory::getDbo();
		$db->setQuery('SELECT `extension_id` FROM `#__extensions` WHERE `type` = "plugin" AND `folder` = "vmcustom" AND `element`="stockable"');
		$jId = $db->loadResult();

		if($jId){
			$db->setQuery('SELECT `virtuemart_custom_id` FROM #__virtuemart_customs WHERE `custom_jplugin_id` = "'.$jId.'" ');
			$cId = $db->loadResult();

			$db->setQuery('SELECT `virtuemart_custom_id` FROM #__virtuemart_customs WHERE `field_type` = "A" ');
			$acId = $db->loadResult();

			if($cId){
				$db->setQuery('UPDATE #__virtuemart_product_customfields SET `virtuemart_custom_id` = "'.$acId.'" WHERE `virtuemart_custom_id` = "'.$cId.'" ');
				$db->execute();
			}
		}
		$db->setQuery('UPDATE #__extensions SET `enabled` = "0" WHERE `extension_id` = "'.$jId.'" ');

	}


	private function updateMoneyBookersToSkrill() {
		$db = JFactory::getDBO ();
		$q="SELECT `extension_id` FROM `#__extensions` WHERE `#__extensions`.`folder` =  'vmpayment' AND `#__extensions`.`element` LIKE  'skrill'";
		$db->setQuery ($q);
		$skrill_jplugin_id = $db->loadResult()  ;
		$app = JFactory::getApplication ();

		$q="SELECT *
			FROM `#__virtuemart_paymentmethods`
			JOIN `#__extensions` ON `#__extensions`.`extension_id` = `#__virtuemart_paymentmethods`.`payment_jplugin_id`
			WHERE `#__extensions`.`folder` =  'vmpayment'
			AND `#__extensions`.`element` LIKE  'moneybookers_%'";
		$db->setQuery ($q);
		$moneybookers = $db->loadObjectList()  ;
		if ($moneybookers) {
			self::$html .= "<h3>Updating MoneyBookers plugin to Skrill</h3>";
			foreach ($moneybookers as $moneybooker) {
				$payment_params=$moneybooker->payment_params;
				$mb_element=str_replace('moneybookers_', '',$moneybooker->element);
				$payment_params='product='.$mb_element.'|'.$payment_params;
				$q = 'UPDATE `#__virtuemart_paymentmethods`
								SET `payment_params`= "'.$payment_params.'" , `payment_jplugin_id` = '.$skrill_jplugin_id.' , `payment_element`= "skrill"
								 WHERE `virtuemart_paymentmethod_id` ='.$moneybooker->virtuemart_paymentmethod_id;
				$db->setQuery($q);
				$db->query();
				$app->enqueueMessage ("Updated payment method: ".$moneybooker->payment_element.". Uses skrill now");
			}

		}


		$q="DELETE FROM  `#__extensions` WHERE  `#__extensions`.`folder` =  'vmpayment'
			AND `#__extensions`.`element` LIKE  'moneybookers%'";
		$db->setQuery($q);
		$db->query();

		$path =JPATH_ROOT .'/plugins/vmpayment';
		$moneybookers_variants=array('', '_acc', '_did','_gir','_idl','_obt', '_pwy','_sft', '_wlt');
		foreach ($moneybookers_variants as $moneybookers_variant) {
			$folder=$path .'/moneybookers'.$moneybookers_variant;
			if (JFolder::exists($folder) ) {
				if (!JFolder::delete($folder)) {
					$app->enqueueMessage ("Failed to delete ". $folder." folder");
				}
			}
			$lang_file=	  JPATH_ROOT .'/administrator/language/en-GB/en-GB.plg_vmpayment_moneybookers'.$moneybookers_variant.'ini';
			if (JFile::exists ($lang_file) ){
				if (!JFile::delete ($lang_file)) {
					$app->enqueueMessage ('Couldnt delete ' . $lang_file);
					return false;
				}
			}
		}

	}

	private function updateShipmentWeight_countries_keys(){

		$db = JFactory::getDBO ();
		$q = 'UPDATE `#__virtuemart_shipmentmethods` SET `shipment_params`= REPLACE(`shipment_params`, "orderamount_start", "min_amount") WHERE `shipment_element` ="weight_countries"';
		$db->setQuery($q);
		$db->execute();

		$q = 'UPDATE `#__virtuemart_shipmentmethods` SET `shipment_params`= REPLACE(`shipment_params`, "orderamount_stop", "max_amount") WHERE `shipment_element` ="weight_countries"';
		$db->setQuery($q);
		$db->execute();
	}


	private function updateOrderingExtensions(){


		$db = JFactory::getDBO ();

		$q = 'UPDATE `#__extensions` SET `ordering`= 20 WHERE `folder` ="vmpayment"';
		$db->setQuery($q);
		$db->query();

		$order = array('paypal','tco','sofort','sofort_ideal','klarna','paybox','heidelpay','skrill','klikandpay','realex_hpp_api','amazon');
		foreach($order as $o=>$el){
			$q = 'UPDATE `#__extensions` SET `ordering`= "'.$o.'" WHERE `element` ="'.$el.'"';
			$db->setQuery($q);
			$db->execute();
		}

		$q = 'UPDATE `#__extensions` SET `ordering`= 100 WHERE `element` ="payzen"';
		$db->setQuery($q);
		$db->execute();

		$q = 'UPDATE `#__extensions` SET `ordering`= 100 WHERE `element` ="systempay"';
		$db->setQuery($q);
		$db->execute();
	}

	/**
	 * Installs a vm plugin into the database
	 *
	 */
	private function installPlugin ($name, $type, $element, $group, $published = 0, $createJPluginTable = 1) {

		$task = vRequest::getCmd ('task');

		if ($task != 'updateDatabase') {
			$data = array();

			$src = $this->path .'/plugins/'. $group .'/'. $element;

			if ($createJPluginTable) {

				$table = JTable::getInstance('extension');
				$data['enabled'] = $published;
				$data['access'] = 1;
				$tableName = '#__extensions';
				$idfield = 'extension_id';

				$data['name'] = $name;
				$data['type'] = $type;
				$data['element'] = $element;
				$data['folder'] = $group;

				$data['client_id'] = 0;

				$db = JFactory::getDBO();
				$q = 'SELECT COUNT(*) FROM `' . $tableName . '` WHERE `element` = "' . $element . '" and folder = "' . $group . '" ';
				$db->setQuery($q);
				$count = $db->loadResult();

				if ($count == 2) {
					$q = 'SELECT ' . $idfield . ' FROM `' . $tableName . '` WHERE `element` = "' . $element . '" and folder = "' . $group . '" ORDER BY  `' . $idfield . '` DESC  LIMIT 0,1';
					$db->setQuery($q);
					$duplicatedPlugin = $db->loadResult();
					$q = 'DELETE FROM `' . $tableName . '` WHERE ' . $idfield . ' = ' . $duplicatedPlugin;
					$db->setQuery($q);
					$db->query();
				}

				//We write ALWAYS in the table,like this the version number is updated
				$data['manifest_cache'] = json_encode(JInstaller::parseXMLInstallFile($src . '/' . $element . '.xml'));

				if ($count == 1) {
					$q = 'SELECT ' . $idfield . ' FROM `' . $tableName . '` WHERE `element` = "' . $element . '" and folder = "' . $group . '" ORDER BY  `' . $idfield . '`';
					$db->setQuery($q);
					$ext_id = $db->loadResult();
					$q = 'UPDATE `#__extensions`  SET `manifest_cache` ="' . $db->escape($data['manifest_cache']) . '" WHERE extension_id=' . $ext_id . ';';
					$db->setQuery($q);
					if (!$db->query()) {
						$app = JFactory::getApplication();
						$app->enqueueMessage(get_class($this) . '::  ' . $db->getErrorMsg());
					}
				} else {
					if (!$table->bind($data)) {
						$app = JFactory::getApplication();
						$app->enqueueMessage('VMInstaller table->bind throws error for ' . $name . ' ' . $type . ' ' . $element . ' ' . $group);
					}

					if (!$table->check($data)) {
						$app = JFactory::getApplication();
						$app->enqueueMessage('VMInstaller table->check throws error for ' . $name . ' ' . $type . ' ' . $element . ' ' . $group);

					}

					if (!$table->store($data)) {
						$app = JFactory::getApplication();
						$app->enqueueMessage('VMInstaller table->store throws error for ' . $name . ' ' . $type . ' ' . $element . ' ' . $group);
					}

					$errors = $table->getErrors();
					foreach ($errors as $error) {
						$app = JFactory::getApplication();
						$app->enqueueMessage(get_class($this) . '::store ' . $error);
					}
				}
			}

		}

		$dst = JPATH_ROOT .'/plugins/'. $group .'/'. $element;

		$success = true;
		if ($task != 'updateDatabase') {
			$success =$this->recurse_copy ($src, $dst);
		}

		//Execute updatePluginTable while package installation, only if the VMPATH_ROOT is set to the root folder

		if(JPATH_ROOT==VMPATH_ROOT){
			if ($success) {
				$this->updatePluginTable ($name, $type, $element, $group, $dst);
			}
		} else {

		}
		$umimodel = VmModel::getModel('updatesmigration');
		$umimodel->updateJoomlaUpdateServer( $type, $element, $dst , $group  );

		$installTask = $count==0 ? 'installed':'updated';
		self::$html .= '<tr><td>' . $name . '</td><td> '.$installTask.'</td></tr>';
		unset($data);
	}


	public function updatePluginTable ($name, $type, $element, $group, $dst) {

		$app = JFactory::getApplication ();

		//Update Tables
		if (!class_exists ('VmConfig')) {
			require(JPATH_ADMINISTRATOR .'/components/com_virtuemart/helpers/config.php');
		}

		if (class_exists ('VmConfig')) {
			$pluginfilename = $dst . '/' . $element . '.php';
			if(file_exists($pluginfilename)){
				require_once ($pluginfilename);	//require_once cause is more failproof and is just for install
			} else {
				$app = JFactory::getApplication ();
				$app->enqueueMessage (get_class ($this) . ':: VirtueMart3 could not find file '.$pluginfilename);
				return false;
			}

			//plgVmpaymentPaypal
			$pluginClassname = 'plg' . ucfirst ($group) . ucfirst ($element);

			//Let's get the global dispatcher
			$dispatcher = JDispatcher::getInstance ();
			$config = array('type' => $group, 'name' => $element, 'params' => '');
			$plugin = new $pluginClassname($dispatcher, $config);

			$_psType = substr ($group, 2);

			$tablename = '#__virtuemart_' . $_psType . '_plg_' . $element;
			$db = JFactory::getDBO ();
			$prefix = $db->getPrefix ();
			$query = 'SHOW TABLES LIKE "' . str_replace ('#__', $prefix, $tablename) . '"';
			$db->setQuery ($query);
			$result = $db->loadResult ();

			if ($result) {
				$SQLfields = $plugin->getTableSQLFields ();
				$loggablefields = $plugin->getTableSQLLoggablefields ();
				$tablesFields = array_merge ($SQLfields, $loggablefields);
				$update[$tablename] = array($tablesFields, array(), array());
				//vmdebug ('install plugin', $update);
				$app->enqueueMessage (get_class ($this) . ':: VirtueMart update ' . $tablename);


				$updater = new GenericTableUpdater();

				$updater->updateMyVmTables ($update);
			}
		} else {
			$app = JFactory::getApplication ();
			$app->enqueueMessage (get_class ($this) . ':: VirtueMart must be installed, or the tables cant be updated ');

		}

	}

	public function installModule ($title, $module, $ordering, $params, $src, $client_id = 0, $position = 'position-4', $access = 1, $alreadyInstalled = true) {
		$table = JTable::getInstance('module');
		$db = $table->getDBO();
		$src .= '/' . $module;
		if (!$alreadyInstalled) {
			$params = '';

			$q = 'SELECT id FROM `#__modules` WHERE `module` = "' . $module . '" ';
			$db->setQuery($q);
			$id = $db->loadResult();

			if (!empty($id)) {
				return;
			}
			$table->load();

			if (empty($table->title)) {
				$table->title = $title;
			}
			if (empty($table->ordering)) {
				$table->ordering = $ordering;
			}
			if (empty($table->published)) {
				$table->published = 1;
			}
			if (empty($table->module)) {
				$table->module = $module;
			}
			if (empty($table->params)) {
				$table->params = $params;
			}
			// table is loaded with access=1
			$table->access = $access;
			if (empty($table->position)) {
				$table->position = $position;
			}
			if (empty($table->client_id)) {
				$table->client_id = $client_id;
			}

			$table->language = '*';

			if (!$table->check()) {
				$app = JFactory::getApplication();
				$app->enqueueMessage('VMInstaller table->check throws error for ' . $title . ' ' . $module . ' ' . $params);
			}

			if (!$table->store()) {
				$app = JFactory::getApplication();
				$app->enqueueMessage('VMInstaller table->store throws error for for ' . $title . ' ' . $module . ' ' . $params);
			}

			$errors = $table->getErrors();
			foreach ($errors as $error) {
				$app = JFactory::getApplication();
				$app->enqueueMessage(get_class($this) . '::store ' . $error);
			}
			// 			}

			$lastUsedId = $table->id;

			$q = 'SELECT moduleid FROM `#__modules_menu` WHERE `moduleid` = "' . $lastUsedId . '" ';
			$db->setQuery($q);
			$moduleid = $db->loadResult();

			$action = '';
			if (empty($moduleid)) {
				$q = 'INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES( "' . $lastUsedId . '" , "0");';
			} else {
				//$q = 'UPDATE `#__modules_menu` SET `menuid`= "0" WHERE `moduleid`= "'.$moduleid.'" ';
			}
			$db->setQuery($q);
			$db->query();
		}


		$q = 'SELECT extension_id FROM `#__extensions` WHERE `element` = "' . $module . '" ';
		$db->setQuery($q);
		$ext_id = $db->loadResult();

		//				$manifestCache = str_replace('"', '\'', $data["manifest_cache"]);
		$action = '';
		$manifest_cache = json_encode(JInstaller::parseXMLInstallFile($src . '/' . $module  . '.xml'));
		if (empty($ext_id)) {
			$q = 'INSERT INTO `#__extensions` 	(`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `ordering`) VALUES
															( "' . $module . '" , "module", "' . $module . '", "", "' . $client_id . '", "1","' . $access . '", "0", "' . $db->escape($manifest_cache) . '", "' . $db->escape($params) . '","' . $ordering . '");';
		} else {
			$q = 'UPDATE `#__extensions`  SET `manifest_cache` ="' . $db->escape($manifest_cache) . '", state=0 WHERE extension_id='.$ext_id.';';
		}
		$db->setQuery($q);
		if (!$db->query()) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(get_class($this) . '::  ' . $db->getErrorMsg());
		}
		$installTask = empty($ext_id) ? 'installed' : 'updated';
		self::$html .= '<tr><td>' . $title . '</td><td> '.$installTask.'</td></tr>';


		//$this->updateJoomlaUpdateServer( 'module', $module, $dst   );
	}

	public function VmModulesAlreadyInstalled () {

		// when the modules are already installed publish=-2
		$table = JTable::getInstance ('module');
		$db = $table->getDBO ();
		$q = 'SELECT count(*) FROM `#__modules` WHERE `module` LIKE "mod_virtuemart_%"';
		$db->setQuery ($q);
		$count = $db->loadResult ();
		return $count;
	}

	public function VmAdminModulesAlreadyInstalled () {

		// when the modules are already installed publish=-2
		$table = JTable::getInstance ('module');
		$db = $table->getDBO ();
		$q = 'SELECT count(*) FROM `#__modules` WHERE `module` LIKE "mod_vmmenu"';
		$db->setQuery ($q);
		$count = $db->loadResult ();
		return $count;
	}


	/**
	 * @author Max Milbers
	 * @param string $tablename
	 * @param string $fields
	 * @param string $command
	 */
	private function alterTable ($tablename, $fields, $command = 'CHANGE') {

		if (empty($this->db)) {
			$this->db = JFactory::getDBO ();
		}

		$query = 'SHOW COLUMNS FROM `' . $tablename . '` ';
		$this->db->setQuery ($query);
		$columns = $this->db->loadColumn (0);

		foreach ($fields as $fieldname => $alterCommand) {
			if (in_array ($fieldname, $columns)) {
				$query = 'ALTER TABLE `' . $tablename . '` ' . $command . ' COLUMN `' . $fieldname . '` ' . $alterCommand;

				$this->db->setQuery ($query);
				$this->db->query ();
			}
		}

	}

	/**
	 *
	 * @author Max Milbers
	 * @param string $table
	 * @param string $field
	 * @param string $fieldType
	 * @return boolean This gives true back, WHEN it altered the table, you may use this information to decide for extra post actions
	 */
	private function checkAddFieldToTable ($table, $field, $fieldType) {

		$query = 'SHOW COLUMNS FROM `' . $table . '` ';
		$this->db->setQuery ($query);
		$columns = $this->db->loadColumn (0);

		if (!in_array ($field, $columns)) {

			$query = 'ALTER TABLE `' . $table . '` ADD ' . $field . ' ' . $fieldType;
			$this->db->setQuery ($query);
			if (!$this->db->query ()) {
				$app = JFactory::getApplication ();
				$app->enqueueMessage ('Install checkAddFieldToTable ' . $this->db->getErrorMsg ());
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}



	/**
	 * copy all $src to $dst folder and remove it
	 *
	 * @author Max Milbers
	 * @param String $src path
	 * @param String $dst path
	 * @param String $type modulesBE, modules, plugins, languageBE, languageFE
	 */
	private function recurse_copy ($src, $dst) {

		if($this->dontMove) return true;
		$dir = opendir ($src);
		$this->createIndexFolder ($dst);

		if (is_resource ($dir)) {
			while (FALSE !== ($file = readdir ($dir))) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir ($src . '/' . $file)) {
						if(!JFolder::create($dst . '/' . $file)){
							$app = JFactory::getApplication ();
							$app->enqueueMessage ('Couldnt create folder ' . $dst . '/' . $file);
						}
						$this->recurse_copy ($src . '/' . $file, $dst . '/' . $file);
					} else {
						if (JFile::exists ($dst . '/' . $file)) {
							if (!JFile::delete ($dst . '/' . $file)) {
								$app = JFactory::getApplication ();
								$app->enqueueMessage ('Couldnt delete ' . $dst . '/' . $file);
								return false;
							}
						}
						if (!JFile::move ($src . '/' . $file, $dst . '/' . $file)) {
							$app = JFactory::getApplication ();
							$app->enqueueMessage ('Couldnt move ' . $src . '/' . $file . ' to ' . $dst . '/' . $file);
							return false;
						}
					}
				}
			}
			closedir ($dir);
			if (JFolder::exists ($src)) {
				JFolder::delete ($src);
			}
		} else {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ('Virtuemart AIO Installer recurse_copy; Couldnt read source directory '.$src.' or target directory does not exits '.$dst);
			return false;
		}
		return true;
	}


	public function uninstall () {

		return TRUE;
	}

	/**
	 * creates a folder with empty html file
	 *
	 * @author Max Milbers
	 *
	 */
	public function createIndexFolder ($path) {

		if (JFolder::create ($path)) {
			/*if (!JFile::exists ($path .'/index.html')) {
				JFile::copy (JPATH_ROOT .'/components/index.html', $path .'/index.html');
			}*/
			return TRUE;
		}
		return FALSE;
	}

}

// pure php no tag
