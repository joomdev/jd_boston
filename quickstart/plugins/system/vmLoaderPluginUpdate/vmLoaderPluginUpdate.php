<?php
/**
 * @plugin vmLoaderPluginUpdate
 * Version 1.0, 2017-08-06
 * @author Reinhold Kainhofer, Open Tools
 * @copyright Copyright (C) 2017 Reinhold Kainhofer - All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl.html GNU/GPL 

 * The Joomla 3.7+ plugin updater tries to load the plugin before
 * updating. Since many plugins in the past assumed that they could only
 * be loaded from within VM, the plugins never load the VM framework,
 * so loading the plugin from the updater fails.
 *
 * This plugin makes sure that, when a plugin update is run, the VM
 * config is loaded properly.
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');

class plgSystemVmLoaderPluginUpdate extends JPlugin {

	function __construct(&$subject, $config = array()) {
		parent::__construct($subject, $config);
		# Only for Joomla 3.7 and above:
		# If a plugin update is run (option=com_installer&view=update&task=update.update),
		# then load the VM config. This approach will load VM even for non-VM
		# plugins, but the overhead of checking whether the updated plugin 
		# really requires VM is way too large compared to the performance hit
		# of loading VM even for non-VM plugins.
		if(version_compare(JVERSION,'3.7.0','ge')) {
			$jinput = JFactory::getApplication()->input;
			$option = $jinput->get('option');
			$view = $jinput->get('view');
			$task = $jinput->get('task');
			if ($option == 'com_installer' && $view == 'update' && $task == 'update.update') {
				if (!class_exists( 'VmConfig' )) {
					$installer = JInstaller::getInstance();
					if($installer){
						$source_path = $installer->getPath('source');
						if(!empty($source_path) and file_exists($source_path.'/administrator/components/com_virtuemart/helpers/config.php')){
							defined('VMPATH_ROOT') or define('VMPATH_ROOT', $source_path);
						}
					}
					defined('VMPATH_ROOT') or define('VMPATH_ROOT', JPATH_ROOT);

					if(!class_exists('VmConfig')) require_once(VMPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
					VmConfig::loadConfig(true,false, false, false);
				}
			}
		}
	}


	function onAfterRoute() {

		$app = JFactory::getApplication();
		//if($app->isClient('administrator')) return;
		if($app->isAdmin()) return;

		$r = $this->params->get('comuserredirect', true);
		if($r){

			$jinput = JFactory::getApplication()->input;
			$option = $jinput->get('option');
			$view = $jinput->get('view');
			$task = $jinput->get('task');
			//$itemID = $jinput->get('Itemid');

			if ($option=='com_users') {

				if($view=='registration' or $task == 'registration.register') {
					$t = '';
					$msg = '';
					if($task == 'registration.register'){
						$t = '&task=saveUser';
						$msg = 'Use the registration of VirtueMart';
					}
					$l = JRoute::_('index.php?option=com_virtuemart&view=user'.$t);
					$app->redirect( $l,$msg);
				}
			}
		}


	}

}
