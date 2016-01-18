<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: RSS aggregator configuration.

if (!defined('idxADMIN') || !USER::$root) die();

# Save configuration
if (!empty($REQUEST['save_s'])) {
    $config = [];
    $config['cache_time']   = empty($REQUEST['cache_time'])   ? 3600 : $REQUEST['cache_time'];
    $config['title_length'] = empty($REQUEST['title_length']) ? 30   : $REQUEST['title_length'];
    $config['desc_length']  = empty($REQUEST['desc_length'])  ? 50   : $REQUEST['desc_length'];
    $config['feeds']        = empty($REQUEST['feeds'])        ? ''   : explode(LF, $REQUEST['feeds']);
    CMS::call('CONFIG')->setSection('rss-aggregator', $config);
    if (!CMS::call('CONFIG')->save()) {
        echo SYSTEM::showError('Cannot save file');
    }
}

$config = CONFIG::getSection('rss-aggregator');
$config['feeds'] = empty($config['feeds']) ? '' : implode(LF, $config['feeds']);

$TPL = new TEMPLATE(__DIR__.DS.'rss-aggregator.tpl');
$TPL->set($config);
echo $TPL->parse();
