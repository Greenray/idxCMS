<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Gallery configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    if (empty($config)) {
        $config['description_length'] = 300;
        $config['message_length']     = 4000;
        $config['images_per_page']    = 9;
        $config['comments_per_page']  = 10;
        $config['random'] = 1;
        $config['last']   = 1;
        CMS::call('CONFIG')->setSection('gallery', $config);
        if (CMS::call('CONFIG')->save())
             echo SYSTEM::showMessage('Configuration saved');
        else echo SYSTEM::showError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = [];
        $config['description_length'] = empty($REQUEST['description_length']) ? 300  : $REQUEST['description_length'];
        $config['message_length']     = empty($REQUEST['message_length'])     ? 4000 : $REQUEST['message_length'];
        $config['images_per_page']    = empty($REQUEST['images_per_page'])    ? 10   : $REQUEST['images_per_page'];
        $config['comments_per_page']  = empty($REQUEST['comments_per_page'])  ? 10   : $REQUEST['comments_per_page'];
        $config['random'] = empty($REQUEST['random']) ? 1 : $REQUEST['random'];
        $config['last']   = empty($REQUEST['last'])   ? 1 : $REQUEST['last'];
        CMS::call('CONFIG')->setSection('gallery', $config);
        if (CMS::call('CONFIG')->save())
             echo SYSTEM::showMessage('Configuration saved');
        else echo SYSTEM::showError('Cannot save file'.' config.ini');
    }

    $config = CONFIG::getSection('gallery');

    $TPL = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TPL->set($config);
    echo $TPL->parse();
}