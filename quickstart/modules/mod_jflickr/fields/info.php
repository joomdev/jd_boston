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
defined( '_JEXEC' ) or die( 'Restricted access' );
//import the necessary class definition for formfield
jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldInfo extends JFormField {
	protected  $type = 'Info';
	protected function getInput()
	{
		return '';
	}

	public function getLabel()
	{
		return '<div style="font-size:12px; line-height:18px; color:#333; padding:10px; margin:10px 0; background: #FAF2B6; height: auto; min-width: 100% important!; max-width: 100% important!;">'.JText::_($this->element['label']).'<div style="clear:both;"></div></div>';
	}
}
