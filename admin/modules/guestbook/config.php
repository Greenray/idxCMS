<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GUESTBOOK - CONFIGURATION

if (!defined('idxADMIN')) die();

$config = CONFIG::getSection('guestbook');

if (isset($init)) {
    if (empty($config)) {
        $config['db-size'] = 100;
        $config['message-length'] = 1000;
        $config['per-page'] = 10;
        $config['allow-guests-post'] = '';
        CMS::call('CONFIG')->setSection('guestbook', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['db-size']           = empty($REQUEST['db-size'])        ? 100  : (int) $REQUEST['db-size'];
        $config['message-length']    = empty($REQUEST['message-length']) ? 1000 : (int) $REQUEST['message-length'];
        $config['per-page']          = empty($REQUEST['per-page'])       ? 10   : (int) $REQUEST['per-page'];
        $config['allow-guests-post'] = FILTER::get('REQUEST', 'allow-guests-post');
        CMS::call('CONFIG')->setSection('guestbook', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>