<?php
defined('JPATH_BASE') or die;

/**
 * @author Max Milbers
 * @copyright Copyright (C) VirtueMart Team - All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 */
jimport('joomla.form.formfield');


/**
 * Creates dropdown for selecting a string customfield
 */
class JFormFieldScustom extends JFormField {

	var $type = 'scustom';

	function getInput() {
		if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		return JHtml::_('select.genericlist',  $this->_getStringCustoms(), $this->name, 'class="inputbox"   ', 'value', 'text', $this->value, $this->id);
	}

	private function _getStringCustoms() {

		$cModel = VmModel::getModel('custom');
		$cModel->_noLimit = true;
		$q = 'SELECT `virtuemart_custom_id` AS value, custom_title AS text FROM `#__virtuemart_customs` WHERE custom_parent_id="0" AND field_type = "S" ';
		$q .= ' AND `published`=1';
		$db = JFactory::getDBO();
		$db->setQuery ($q);
		$l = $db->loadObjectList ();
		$eOpt = JHtml::_('select.option', '0', vmText::_('COM_VIRTUEMART_NONE'));
		array_unshift($l,$eOpt);

		return $l;

	}

}