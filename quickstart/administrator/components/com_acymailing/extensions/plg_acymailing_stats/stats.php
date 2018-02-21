<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.9.1
 * @author	acyba.com
 * @copyright	(C) 2009-2018 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><?php

class plgAcymailingStats extends JPlugin{
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'stats');
			$this->params = new acyParameter($plugin->params);
		}
		$this->acypluginsHelper = acymailing_get('helper.acyplugins');
	}

	function acymailing_replacetags(&$email, $send = true){
		$this->statPicture($email, $send);
	}

	function statPicture(&$email, $send = true){
		if(!empty($email->altbody)){
			$email->altbody = str_replace(array('{statpicture}', '{nostatpicture}'), '', $email->altbody);
		}
		if(((isset($email->sendHTML) && !$email->sendHTML) || (isset($email->html) && !$email->html))
			|| empty($email->type)
			|| !in_array($email->type, array('news', 'autonews', 'followup', 'welcome', 'unsub', 'joomlanotification', 'action'))
			|| strpos($email->body, '{nostatpicture}')){
			$email->body = str_replace(array('{statpicture}', '{nostatpicture}'), '', $email->body);
			return;
		}

		if(!$send){
			$pictureLink = ACYMAILING_LIVE.$this->params->get('picture', 'media/com_acymailing/images/statpicture.png');
		}else {
			$config = acymailing_config();
			$itemId = $config->get('itemid', 0);
			$item = empty($itemId) ? '' : '&Itemid=' . $itemId;
			$pictureLink = acymailing_frontendLink('index.php?option=com_acymailing&ctrl=statistics&mailid=' . $email->mailid . '&subid={subtag:subid}' . $item, false);
		}

		$widthsize = $this->params->get('width', 50);
		$heightsize = $this->params->get('height', 1);
		$width = empty($widthsize) ? '' : ' width="'.$widthsize.'" ';
		$height = empty($heightsize) ? '' : ' height="'.$heightsize.'" ';

		$statPicture = '<img class="spict" alt="'.$this->params->get('alttext', '').'" src="'.$pictureLink.'"  border="0" '.$height.$width.'/>';

		if(strpos($email->body, '{statpicture}')){
			$email->body = str_replace('{statpicture}', $statPicture, $email->body);
		}elseif(strpos($email->body, '</body>')) $email->body = str_replace('</body>', $statPicture.'</body>', $email->body);
		else $email->body .= $statPicture;
	}//endfct

	function acymailing_getstatpicture(){
		return $this->params->get('picture', 'media/com_acymailing/images/statpicture.png');
	}

	function onAcyDisplayTriggers(&$triggers){
		$triggers['opennews'] = acymailing_translation('ON_OPEN_NEWS');
	}

	function onAcyDisplayFilters(&$type, $context = "massactions"){

		if($context != "massactions" AND !$this->params->get('displayfilter_'.$context, false)) return;

		$type['deliverstat'] = acymailing_translation('STATISTICS');

		$allemails = acymailing_loadObjectList("SELECT `mailid`,CONCAT(`subject`,' [',".acymailing_escapeDB(acymailing_translation('ACY_ID').' ').", CAST(`mailid` AS char),']') as 'value' FROM `#__acymailing_mail` WHERE `type` IN('news','welcome','unsub','followup','notification','joomlanotification') ORDER BY `senddate` DESC LIMIT 5000");
		$element = new stdClass();
		$element->mailid = 0;
		$element->value = acymailing_translation('EMAIL_NAME');
		array_unshift($allemails, $element);

		$actions = array();
		$actions[] = acymailing_selectOption('open', acymailing_translation('OPEN'));
		$actions[] = acymailing_selectOption('notopen', acymailing_translation('NOT_OPEN'));
		$actions[] = acymailing_selectOption('failed', acymailing_translation('FAILED'));
		if(acymailing_level(3)) $actions[] = acymailing_selectOption('bounce', acymailing_translation('BOUNCES'));
		$actions[] = acymailing_selectOption('htmlsent', acymailing_translation('SENT_HTML'));
		$actions[] = acymailing_selectOption('textsent', acymailing_translation('SENT_TEXT'));
		$actions[] = acymailing_selectOption('notsent', acymailing_translation('NOT_SENT'));

		$return = '<div id="filter__num__deliverstat">'.acymailing_select($actions, "filter[__num__][deliverstat][action]", 'class="inputbox" onchange="countresults(__num__);" size="1"', 'value', 'text');
		$return .= ' '.acymailing_select($allemails, "filter[__num__][deliverstat][mailid]", 'onchange="countresults(__num__)" class="inputbox" size="1" style="max-width:200px"', 'mailid', 'value').'</div>';

		return $return;
	}

	function onAcyProcessFilterCount_deliverstat(&$query, $filter, $num){
		$this->onAcyProcessFilter_deliverstat($query, $filter, $num);
		return acymailing_translation_sprintf('SELECTED_USERS', $query->count());
	}

	function onAcyProcessFilter_deliverstat(&$query, $filter, $num){

		$alias = 'stats'.$num;
		$jl = '#__acymailing_userstats AS '.$alias.' ON '.$alias.'.subid = sub.subid';
		if(!empty($filter['mailid'])) $jl .= ' AND '.$alias.'.mailid = '.intval($filter['mailid']);

		$query->leftjoin[$alias] = $jl;

		if($filter['action'] == 'open'){
			$where = $alias.'.open > 0';
		}elseif($filter['action'] == 'notopen'){
			if(empty($filter['mailid'])) {
				unset($query->leftjoin[$alias]);
				$usersNeverOpened = acymailing_loadResultArray('SELECT subid FROM #__acymailing_userstats GROUP BY subid HAVING MAX(open) = 0');
				if(empty($usersNeverOpened)) $usersNeverOpened = array(0);
				$where = 'sub.subid IN ('.implode(',', $usersNeverOpened).')';
			}else{
				$where = $alias.'.open = 0';
			}
		}elseif($filter['action'] == 'failed'){
			$where = $alias.'.fail = 1';
		}elseif($filter['action'] == 'bounce'){
			$where = $alias.'.bounce = 1';
		}elseif($filter['action'] == 'htmlsent'){
			$where = $alias.'.html = 1';
		}elseif($filter['action'] == 'textsent'){
			$where = $alias.'.html = 0';
		}elseif($filter['action'] == 'notsent'){
			$where = $alias.'.subid IS NULL';
		}

		$query->where[] = $where;
	}

}//endclass
