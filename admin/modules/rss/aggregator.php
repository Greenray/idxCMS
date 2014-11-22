<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - RSS AGGREGATOR

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

# Save configuration
if (!empty($REQUEST['save_s'])) {
    $config = array();
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
?>