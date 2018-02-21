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

require_once dirname(__FILE__) . '/helper.php';

$class_sfx = htmlspecialchars($params->get('class_sfx'));
$count1 = htmlspecialchars($params->get('count'));

// Add style and css to header
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root().'modules/mod_testimonies/assets/css/testimonies.css');

// Get Params
$ot_style = $params->get('ot_testimonies_style',1);
$ot_speed = $params->get('ot_speed',3000);

if ($params->get('include_bootstrap')==1) {
	$doc->addScript(JURI::root()."modules/mod_testimonies/assets/js/bootstrap.min.js");
	$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
}   

$lists = modTestimoniesHelper::getList($params);
// Get module sfx class
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

if($ot_style == 1) :
	require( JModuleHelper::getLayoutPath( 'mod_testimonies', $params->get('layout', 'slider_1_column') ) );
elseif($ot_style == 2) :
	require( JModuleHelper::getLayoutPath( 'mod_testimonies', $params->get('layout', 'slider_with_2_columns') ) );
elseif($ot_style == 3) :
	require( JModuleHelper::getLayoutPath( 'mod_testimonies', $params->get('layout', 'thumbnails') ) );
elseif($ot_style == 4) :
	require( JModuleHelper::getLayoutPath( 'mod_testimonies', $params->get('layout', 'grid') ) );	
elseif($ot_style == 5) :
	require( JModuleHelper::getLayoutPath( 'mod_testimonies', $params->get('layout', 'list') ) );	
endif;