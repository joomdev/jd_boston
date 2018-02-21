<?php

// no direct access
/**
 * @package     Joomla.Site
 * @subpackage  JD Team ShowCase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 
defined('_JEXEC') or die('Restricted access');

class modJDShowCase
{
	
	function modJDShowCaseHelper(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__jd_team_members');
		$query->where('state = 1');
		$query->order('ordering ASC');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}
}

