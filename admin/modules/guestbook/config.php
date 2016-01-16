<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Guestbook configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    if (empty($config)) {
        $config['db_size']           = 100;
        $config['message_length']    = 1000;
        $config['per_page']          = 10;
        $config['allow_guests_post'] = '';
        CMS::call('CONFIG')->setSection('guestbook', $config);
        if (CMS::call('CONFIG')->save())
             echo SYSTEM::showMessage('Configuration saved');
        else echo SYSTEM::showError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['db_size']           = empty($REQUEST['db_size'])        ? 100  : (int) $REQUEST['db_size'];
        $config['message_length']    = empty($REQUEST['message_length']) ? 1000 : (int) $REQUEST['message_length'];
        $config['per_page']          = empty($REQUEST['per_page'])       ? 10   : (int) $REQUEST['per_page'];
        CMS::call('CONFIG')->setSection('guestbook', $config);
        if (CMS::call('CONFIG')->save())
             echo SYSTEM::showMessage('Configuration saved');
        else echo SYSTEM::showError('Cannot save file'.' config.ini');
    }

    $config = CONFIG::getSection('guestbook');

    $TPL = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TPL->set($config);
    echo $TPL->parse();
}