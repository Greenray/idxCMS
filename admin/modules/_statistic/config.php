<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistic
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['user-ua']   = empty($REQUEST['user-ua'])   ? '' : '1';
    $config['spider-ip'] = empty($REQUEST['spider-ip']) ? '' : '1';
    $config['spider-ua'] = empty($REQUEST['spider-ua']) ? '' : '1';
    CMS::call('CONFIG')->setSection('statistic', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save file');
    }
}

$config = CONFIG::getSection('statistic');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
