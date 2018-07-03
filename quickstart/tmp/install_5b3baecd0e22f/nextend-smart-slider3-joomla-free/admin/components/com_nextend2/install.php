<?php
jimport("nextend2.nextend.joomla.library");
N2Base::registerApplication(JPATH_SITE . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . 'nextend2/nextend/library/applications/system/N2SystemApplicationInfo.php');
N2Base::getApplication("system")->getApplicationType('backend')->render(array(
    "controller" => "install",
    "action"     => "index",
    "useRequest" => false
), array(true));
n2_exit();

$asset = JTable::getInstance('asset');
$exists = $asset->loadByName('com_nextend2');

if(!$exists || $asset->rules == '{}'){
    $asset->rules = '{"core.manage":{"6":1,"7":1},"nextend.config":{"6":1,"7":1},"nextend.visual.edit":{"6":1,"7":1},"nextend.visual.delete":{"6":1,"7":1}}';
    
    if (!$asset->check() || !$asset->store()) {
        throw new RuntimeException($asset->getError());
    }
}