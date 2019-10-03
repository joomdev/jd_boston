<?php

/**
 * @package	JD Photo Section
 * @subpackage  mod_slider
 * @version	1.0
 * @author	Joomdev.com
 * @copyright	Copyright (C) 2008 - 2018 Joomdev.com. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
require_once (dirname(__FILE__) . '/' . 'helper.php');
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'media/mod_gallery/css/mod_jd_gallery.css');
require JModuleHelper::getLayoutPath('mod_gallery', $params->get('layout', 'default'));
