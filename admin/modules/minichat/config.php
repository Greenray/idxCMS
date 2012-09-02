<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - MINICHAT - CONFIGURATION

if (!defined('idxADMIN')) die();

$config = CONFIG::getSection('minichat');
if (isset($init)) {
    if (empty($config)) {
        $config['db-size'] = 100;
        $config['message-length'] = 200;
        $config['mess-to-show']   = 5;
        CMS::call('CONFIG')->setSection('minichat', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['db-size']        = empty($REQUEST['db-size'])        ? 100 : (int) $REQUEST['db-size'];
        $config['message-length'] = empty($REQUEST['message-length']) ? 200 : (int) $REQUEST['message-length'];
        $config['mess-to-show']   = empty($REQUEST['mess-to-show'])   ? 5   : (int) $REQUEST['mess-to-show'];
        if ($config['mess-to-show'] > $config['db-size']) {
            $config['mess-to-show'] = $config['db-size'];
        }
        CMS::call('CONFIG')->setSection('minichat', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>