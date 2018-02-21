<?php
/**
 * @package		jFlickr
 * @subpackage	jFlickr
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2014 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.4.2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
class JElementHeader extends JElement {
	var	$_name = 'header';
	function fetchElement($name, $value, &$node, $control_name){
		// Output
		return '
		<div style="font-weight:bold;font-size:14px;color:#fff;padding:4px;margin:0;background:#4D7502;">
			'.JText::_($value).'
		</div>
		';
	}
	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
