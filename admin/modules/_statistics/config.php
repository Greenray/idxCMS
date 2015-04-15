<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistics
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['user-ua']   = empty($REQUEST['user-ua'])   ? '' : '1';
    $config['spider-ip'] = empty($REQUEST['spider-ip']) ? '' : '1';
    $config['spider-ua'] = empty($REQUEST['spider-ua']) ? '' : '1';
    CMS::call('CONFIG')->setSection('statistics', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save file');
    }
}

$config = CONFIG::getSection('statistics');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
