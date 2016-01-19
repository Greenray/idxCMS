<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Statistics configuration.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['user_browser'] = empty($REQUEST['user_browser']) ? '' : '1';
    $config['spider_ip']    = empty($REQUEST['spider_ip'])    ? '' : '1';
    $config['spider_agent'] = empty($REQUEST['spider_agent']) ? '' : '1';
    CMS::call('CONFIG')->setSection('statistics', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration saved');
    else ShowError('Cannot save file'.' config.ini');
}

$config = CONFIG::getSection('statistics');

$TPL = new TEMPLATE(__DIR__.DS.'config.tpl');
$TPL->set($config);
echo $TPL->parse();
