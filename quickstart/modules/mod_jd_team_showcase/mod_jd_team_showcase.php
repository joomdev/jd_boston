<?php
/**
 * @package     Joomla.Site
 * @subpackage  JD Team ShowCase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once (dirname ( __FILE__ ) . '/' . 'helper.php');
$app        = JFactory::getApplication();
$teamMembers = modJDShowCase::modJDShowCaseHelper();
require JModuleHelper::getLayoutPath('mod_jd_team_showcase', $params->get('layout', 'default'));
