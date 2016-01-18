<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module Gallery: Catalogs configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    if (empty($config)) {
        $config['description_length'] = 300;
        $config['message_length']     = 4000;
        $config['items_per_page']     = 10;
        $config['comments_per_page']  = 10;
        CMS::call('CONFIG')->setSection('catalogs', $config);
        if (CMS::call('CONFIG')->save())
             echo SYSTEM::showMessage('Configuration saved');
        else echo SYSTEM::showError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = [];
        $config['description_length'] = empty($REQUEST['description_length']) ? 300  : $REQUEST['description_length'];
        $config['message_length']     = empty($REQUEST['message_length'])     ? 4000 : $REQUEST['message_length'];
        $config['items_per_page']     = empty($REQUEST['items_per_page'])     ? 10   : $REQUEST['items_per_page'];
        $config['comments_per_page']  = empty($REQUEST['comments_per_page'])  ? 10   : $REQUEST['comments_per_page'];
        CMS::call('CONFIG')->setSection('catalogs', $config);
        if (!CMS::call('CONFIG')->save()) {
            echo SYSTEM::showError('Cannot save file'.' config.ini');
        }
    }

    $config = CONFIG::getSection('catalogs');

    $TPL = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TPL->set($config);
    echo $TPL->parse();
}