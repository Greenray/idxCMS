<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Guestbook
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
            ShowMessage('Cannot save file');
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
            ShowMessage('Cannot save file');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
