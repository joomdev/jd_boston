<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$title      = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ? $item->anchor_css : '';

$linktype   = $item->title;

if ($item->menu_image)
{
	if ($item->menu_image_css)
	{
		$image_attributes['class'] = $item->menu_image_css;
		$linktype = JHtml::_('image', $item->menu_image, $item->title, $image_attributes);
	}
	else
	{
		$linktype = JHtml::_('image', $item->menu_image, $item->title);
	}

	if ($item->params->get('menu_text', 1))
	{
		$linktype .= '<span class="image-title">' . $item->title . '</span>';
	}
}
elseif (stripos($anchor_css, "mod_") !== false)
{
    $start = strrpos($anchor_css, "mod_");
    $end = strrpos($anchor_css, "-");
    $module_name = substr($anchor_css, $start, $end);

    $modId = substr($anchor_css, $end+1);

    jimport( 'joomla.application.module.helper' );
    $rmodule = JModuleHelper::getModuleById( $modId);

	vmdebug('Override seperator used',$module,$rmodule->params,$rmodule,$modId);
    $linktype = JModuleHelper::renderModule($rmodule);

	//echo JHtml::_('link', JFilterOutput::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
	//return;
}

?>
<span class="separator <?php echo $anchor_css; ?>"<?php echo $title; ?>><?php echo $linktype; ?></span>
