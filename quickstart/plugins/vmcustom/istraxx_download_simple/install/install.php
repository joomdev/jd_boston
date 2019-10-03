<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class plgVmCustomIstraxx_download_simpleInstallerScript{

	function install($parent) {

		$this->update($parent);
	}
 
	function uninstall($parent) {

	}
 
	function update($parent) {
		$current = dirname(__FILE__);

		if(!class_exists('GenericTableUpdater')) require(VMPATH_ADMIN .'/helpers/tableupdater.php');
		$updater = new GenericTableUpdater();

		$updater->updateMyVmTables($current .'/install.sql');

	}
 
	function preflight($type, $parent) {
		if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
	}
 
	function postflight($type, $parent) {

	}
	
}