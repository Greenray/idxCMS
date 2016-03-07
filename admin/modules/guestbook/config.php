<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Guestbook configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    $config['db_size']           = 100;
    $config['message_length']    = 1000;
    $config['per_page']          = 10;
    $config['allow_guests_post'] = '';
    CMS::call('CONFIG')->setSection('guestbook', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration has been saved');
    else ShowError('Cannot save file'.' config.ini');
} else {
    if (!empty($REQUEST['save'])) {
        $config['db_size']           = empty($REQUEST['db_size'])        ? 100  : $REQUEST['db_size'];
        $config['message_length']    = empty($REQUEST['message_length']) ? 1000 : $REQUEST['message_length'];
        $config['per_page']          = empty($REQUEST['per_page'])       ? 10   : $REQUEST['per_page'];
        CMS::call('CONFIG')->setSection('guestbook', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }

    $config = CONFIG::getSection('guestbook');

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TEMPLATE->set($config);
    echo $TEMPLATE->parse();
}