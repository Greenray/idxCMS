<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Forum configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    if (empty($config)) {
        $config['topics_per_page']  = 10;
        $config['replies_per_page'] = 10;
        $config['message_length']   = 4000;
        CMS::call('CONFIG')->setSection('forum', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = [];
        $config['topics_per_page']  = empty($REQUEST['topics_per_page'])  ? 10   : $REQUEST['topics_per_page'];
        $config['replies_per_page'] = empty($REQUEST['replies_per_page']) ? 10   : $REQUEST['replies_per_page'];
        $config['message_length']   = empty($REQUEST['message_length'])   ? 4000 : $REQUEST['message_length'];
        CMS::call('CONFIG')->setSection('forum', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }

    $config = CONFIG::getSection('forum');

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TEMPLATE->set($config);
    echo $TEMPLATE->parse();
}