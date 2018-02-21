<?php
/**
* @package 		OT Testimonies for Joomla! 3.3 
* @version 		$Id: mod_testimonies.php - Aug 2016  OmegaTheme 
* @author 		OmegaTheme Extensions (services@omegatheme.com) - http://omegatheme.com 
* @copyright 	Copyright(C) 2016 - OmegaTheme Extensions 
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/
// no direct access

defined('_JEXEC') or die;

class modTestimoniesHelper {

    public static function getList($params)

	{
		$db = JFactory::getDBO();    

        $query  = $db->getQuery(true);
		
		$where = "state = 1";
		if($params->get('show_featured')) $where .= " AND featured = 1";

        $query->select('*')

         ->from('#__testimonies')

         ->where($where)

         ->order('id desc');

        $db->setQuery($query, 0, $params->get('count'));

        $result = $db->loadObjectList();

		return $result;

	}



}