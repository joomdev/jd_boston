<?php
	defined('_JEXEC') or die();
/**
 * Layout for reminder field
 *
 * @version $Id: default.php 460 2015-01-14 17:25:07Z milbo $
 * @subpackage Plugins -${PHING.GROUP} - ${PHING.FILENAME} - ${PHING.VERSION}
 * @author ${PHING.AUTHOR.MAX}
 * @copyright  Copyright (C) 2018 - 2018 iStraxx UG (haftungsbeschränkt). All rights reserved.
 * @license LGPLv3
 */


class JFormFieldTeaser extends JFormField{

	var	$_name = 'teaser';

	function getLabel() {
		$config = new stdClass();
		$config->fieldname = $this->_name;
		$config->plugin = (string) $this->element['name'];
		$config->psType = 'vmcustom';
		$config->layout = (string) $this->element['layout'];
		echo  $this->renderByRemLayout($config);
	}

	static public function renderByRemLayout($config ){

		$layout = self::_getRemLayoutPath ($config->plugin,  $config->psType, $config->layout);
		if($layout){
			ob_start ();
			include ($layout);
			return ob_get_clean ();
		}
	}

	static public function _getRemLayoutPath ($pluginName, $group, $layout = 'default') {
		$layoutPath='';
		jimport ('joomla.filesystem.file');
		$defaultPath          = VMPATH_ROOT .'/plugins/'. $group . '/' . $pluginName .'/layouts/'. $layout . '.php';
		$defaultPath1          = VMPATH_ROOT .'/plugins/'. $group . '/' . $pluginName. '/' . $pluginName .'/layouts/'. $layout . '.php';
		$defaultPath2          = VMPATH_ROOT .'/modules/' . $pluginName .'/layouts/'. $layout . '.php';

		if (JFile::exists ($defaultPath)) {
			$layoutPath = $defaultPath;
		} else if (JFile::exists ($defaultPath1)) {
			$layoutPath = $defaultPath1;
		}else if (JFile::exists ($defaultPath2)) {
			$layoutPath = $defaultPath2;
		}

		if (empty($layoutPath)) {
			$warn='The layout: '. $layout. ' does not exist in:';
			$warn.='<br />'.$defaultPath;
			if (!empty($defaultPathWithGroup)) {
				$warn.='<br />'.$defaultPathWithGroup .'<br />';
			}

			vmWarn($warn);
			throw new Exception('Layout missing '.$defaultPath);
			return false;
		}
		return $layoutPath;
	}

}

