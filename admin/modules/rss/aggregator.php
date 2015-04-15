<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - RSS
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::$root) die();

# Save configuration
if (!empty($REQUEST['save_s'])) {
    $config = [];
    $config['cache-time']   = empty($REQUEST['cache-time'])   ? 3600 : (int) $REQUEST['cache-time'];
    $config['title-length'] = empty($REQUEST['title-length']) ? 30   : (int) $REQUEST['title-length'];
    $config['desc-length']  = empty($REQUEST['desc-length'])  ? 50   : (int) $REQUEST['desc-length'];
    $config['feeds']        = empty($REQUEST['feeds'])        ? ''   : explode(LF, $REQUEST['feeds']);
    CMS::call('CONFIG')->setSection('rss-aggregator', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save file');
    }
}

$config = CONFIG::getSection('rss-aggregator');
$config['feeds'] = empty($config['feeds']) ? '' : implode(LF, $config['feeds']);
$TPL = new TEMPLATE(dirname(__FILE__).DS.'rss-aggregator.tpl');
echo $TPL->parse($config);
