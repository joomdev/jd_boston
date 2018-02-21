<?php
/**
 * JFlickr is a port of the Jquery Flickr Script by Project Atomic http://www.projectatomic.com/2008/04/jquery-flickr/
 *
 * @package		jFlickr
 * @subpackage	jFlickr
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.4.1
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$document =JFactory::getDocument();


// Import the file / foldersystem
jimport( 'joomla.filesystem.file' );

// Sets variables so we can check if framework or library is present
$app = JFactory::getApplication();
$framework = JPATH_ROOT . '/templates/' . $app->getTemplate() . '/includes/config.php';
$jblibrary = JPATH_SITE . '/media/plg_jblibrary/helpers/image.php';

// Only test for zgf in versions prior to J3.0
$zgf = 0;
if (version_compare(JVERSION, '3.0', '<=')) {
	// Checks to see if framework is installed
	if (file_exists($framework))
	{
	require_once($framework);
	$zgf = 1;
	$library = JURI::base(true) . '/media/zengridframework/';
	}
	// Checks to see if JB Library is installed
	elseif (file_exists($jblibrary))
	{
	require_once($jblibrary);
	$zgf = 0;
	$library = JURI::base(true) . '/media/plg_jblibrary/';
	}
	// Else throw an error to let the user know
	else {
	echo '<div style="font-size:12px;font-family: helvetica neue, arial, sans serif;width:600px;margin:0 auto;background: #f9f9f9;border:1px solid #ddd ;margin-top:100px;padding:40px"><h3>Ooops. It looks like JbLibrary plugin or the Zen Grid Framework plugin is not installed!</h3> <br />Please install it and ensure that you have enabled the plugin by navigating to extensions > plugin manager. <br /><br />JB Library is a free Joomla extension that you can download directly from the <a href="http://www.joomlabamboo.com/joomla-extensions/jb-library-plugin-a-free-joomla-jquery-plugin">Joomla Bamboo website</a>.</div>';
	}
}


if (version_compare(JVERSION, '1.6', '>='))
{

	// Test to see if cache is enabled
	if ($app->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}
else
{
	// Test to see if cache is enabled
	if ($mainframe->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}

$modbase = JURI::base(true).'/modules/mod_jflickr/';
$scripts = $params->get( 'scripts', 1);
$number	= $params->get( 'number', '8' );
$size = $params->get( 'size', 'original' );
$tsize = $params->get( 'tsize', 's' );
$type = $params->get( 'type', 'photoset' );
$photoset_id = $params->get( 'photoset_id', '981332' );
$text = $params->get( 'text', '' );
$user_id = $params->get( 'user_id', '' );
$group_id = $params->get( 'group_id', '' );
$sort = $params->get( 'sort', 'relevance' );
$tags = $params->get( 'tags', '' );
$random = $params->get( 'random', 'false' );
$fancyBox = $params->get( 'fancybox', 'yes' );
$fancyBoxScript = $params->get( 'fancyboxScript', 'yes' );
$fancyEasing = $params->get( 'fancyEasing', 'yes' );
$fancyOverlayShow = $params->get('fancyOverlayShow','true');
$fancyOverlay = $params->get('fancyOverlay','0.6');
$fancyPadding = str_replace('px', '', $params->get('fancyPadding','20'));
$moduleID = $module->id;
$apiKey = $params->get( 'apiKey', 'f28804be7a09c5845676349c7e47d636' );

if ($type =="user") {
	$type1 ="search";
}
if ($type =="group") {
	$type1 ="search";
}

$zoomClass = 'flickrZoom';
	if(!$cache) {
		$document->addStyleDeclaration('.gallery-flickr ul li {list-style-type:none;float:left;background: none;margin-left:0}.gallery-flickr ul {margin: 0} #right .gallery-flickr ul li a,#left .gallery-flickr ul li a,.gallery-flickr ul li a {float:left;margin:0 4px 4px 0;padding: 0;background:none;border: 0;} .gallery-flickr ul li a:hover {background: #ddd} #gallery-flickr {padding: 0;line-height: 0;margin: 0} .clearfix {clear:both}');

		if (($fancyBoxScript == "yes") && ($fancyBox == "yes")){
			$document->addStyleSheet($modbase.'js/jquery.fancybox/jquery.fancybox-1.3.4.css');
			$zoomClass .= $moduleID;
		}

		$document->addScript($modbase . "js/JFlickr.js");

		if (($fancyBoxScript == "yes") && ($fancyBox == "yes")){
				$document->addScript($modbase . "js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js");
		}

		if ($fancyEasing == "yes"){
				$document->addScript($modbase . "js/jquery.fancybox/jquery.easing-1.3.pack.js");
		}
	}


require(JModuleHelper::getLayoutPath('mod_jflickr', 'default')); ?>
