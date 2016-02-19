<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Minichat configuration.

if (!defined('idxADMIN')) die();

if (isset($init)) {
    if (empty($config)) {
        $config['db_size']        = 100;
        $config['message_length'] = 200;
        $config['mess_to_show']   = 5;
        CMS::call('CONFIG')->setSection('minichat', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['db_size']        = empty($REQUEST['db_size'])        ? 100 : $REQUEST['db_size'];
        $config['message_length'] = empty($REQUEST['message_length']) ? 200 : $REQUEST['message_length'];
        $config['mess_to_show']   = empty($REQUEST['mess_to_show'])   ? 5   : $REQUEST['mess_to_show'];
        if ($config['mess_to_show'] > $config['db_size']) {
            $config['mess_to_show'] = $config['db_size'];
        }
        CMS::call('CONFIG')->setSection('minichat', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }

    $config = CONFIG::getSection('minichat');

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TEMPLATE->set($config);
    echo $TEMPLATE->parse();
}