<?php

jimport("nextend2.nextend.joomla.library");
N2Base::registerApplication(JPATH_SITE . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . 'nextend2/smartslider/smartslider/N2SmartsliderApplicationInfo.php');
N2Base::getApplication("smartslider")->getApplicationType('backend')->render(array(
    "controller" => "install",
    "action"     => "index",
    "useRequest" => false
), array(true));
n2_exit();

$asset = JTable::getInstance('asset');
$exists = $asset->loadByName('com_smartslider3');

if(!$exists || $asset->rules == '{}'){
    $asset->rules = '{"core.manage":{"6":1,"7":1},"smartslider.config":{"6":1,"7":1},"smartslider.edit":{"6":1,"7":1},"smartslider.delete":{"6":1,"7":1}}';
    
    if (!$asset->check() || !$asset->store()) {
        throw new RuntimeException($asset->getError());
    }
}