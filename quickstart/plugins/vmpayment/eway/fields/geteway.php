<?php
/**
 * @version $Id: geteway.php 9789 2018-03-12 13:27:14Z alatak $
 *
 * @author Valérie Isaksen
 * @package VirtueMart
 * @copyright Copyright (c) 2004 - 2012 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('JPATH_BASE') or die();

/**
 * Renders a label element
 */

jimport('joomla.form.formfield');
class JFormFieldGetEway extends JFormField {

var $type = 'getEway';

	function getInput() {
		vmJsApi::addJScript( '/plugins/vmpayment/eway/assets/js/admin.js');

		//TODO
			$url = "";

		$html = '<p><a class="signin-button-link" href="' . $url . '" target="_blank">' . vmText::_('VMPAYMENT_EWAY_GET') . '</a>';

		return $html;
	}

	protected function getLang() {


		$language = JFactory::getLanguage();
		$tag = strtolower(substr($language->get('tag'), 0, 2));
		return $tag;
	}


}