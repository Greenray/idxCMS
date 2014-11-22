<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - CATALOGS - CONFIGURATION

if (!defined('idxADMIN')) die();

$config = CONFIG::getSection('catalogs');

if (isset($init)) {
    if (empty($config)) {
        $config['description-length'] = 300;
        $config['comment-length']     = 2000;
        $config['items-per-page']     = 10;
        $config['comments-per-page']  = 10;
        CMS::call('CONFIG')->setSection('catalogs', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save file');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = array();
        $config['description-length'] = empty($REQUEST['description-length']) ? 300  : (int) $REQUEST['description-length'];
        $config['comment-length']     = empty($REQUEST['comment-length'])     ? 2000 : (int) $REQUEST['comment-length'];
        $config['items-per-page']     = empty($REQUEST['items-per-page'])     ? 10   : (int) $REQUEST['item-per-page'];
        $config['comments-per-page']  = empty($REQUEST['comments-per-page'])  ? 10   : (int) $REQUEST['comments-per-page'];
        CMS::call('CONFIG')->setSection('catalogs', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save file');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>