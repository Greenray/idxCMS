<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Statistics configuration.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['user_browser'] = empty($REQUEST['user_browser']) ? '' : '1';
    $config['spider_ip']    = empty($REQUEST['spider_ip'])    ? '' : '1';
    $config['spider_agent'] = empty($REQUEST['spider_agent']) ? '' : '1';
    CMS::call('CONFIG')->setSection('statistics', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration has been saved');
    else ShowError('Cannot save file'.' config.ini');
}

$config = CONFIG::getSection('statistics');

$TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
$TEMPLATE->set($config);
echo $TEMPLATE->parse();
