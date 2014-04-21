<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GALLERIES - CONFIGURATION

if (!defined('idxADMIN')) die();

$config = CONFIG::getSection('galleries');

if (isset($init)) {
    if (empty($config)) {
        $config['description-length'] = 300;
        $config['comment-length']     = 2000;
        $config['images-per-page']    = 9;
        $config['comments-per-page']  = 10;
        $config['random'] = 1;
        $config['last']   = 1;
        CMS::call('CONFIG')->setSection('galleries', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = array();
        $config['description-length'] = empty($REQUEST['description-length']) ? 300  : (int) $REQUEST['description-length'];
        $config['comment-length']     = empty($REQUEST['comment-length'])     ? 2000 : (int) $REQUEST['comment-length'];
        $config['images-per-page']    = empty($REQUEST['images-per-page'])    ? 10   : (int) $REQUEST['images-per-page'];
        $config['comments-per-page']  = empty($REQUEST['comments-per-page'])  ? 10   : (int) $REQUEST['comments-per-page'];
        $config['random'] = empty($REQUEST['random']) ? 1 : (int) $REQUEST['random'];
        $config['last'] = empty($REQUEST['last']) ? 1 : (int) $REQUEST['last'];
        CMS::call('CONFIG')->setSection('galleries', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>