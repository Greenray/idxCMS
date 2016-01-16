<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Users configuration.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['flood']       = empty($REQUEST['flood'])       ? 120 : (int) $REQUEST['flood'];
    $config['nick_length'] = empty($REQUEST['nick_length']) ? 1   : (int) $REQUEST['nick_length'];
    $config['timeout']     = empty($REQUEST['timeout'])     ? 300 : (int) $REQUEST['timeout'];
    CMS::call('CONFIG')->setSection('user', $config);

    $config = [];
    $config['width']  = empty($REQUEST['width'])  ? 70    : (int) $REQUEST['width'];
    $config['height'] = empty($REQUEST['height']) ? 70    : (int) $REQUEST['height'];
    $config['size']   = empty($REQUEST['size'])   ? 50000 : (int) $REQUEST['size'];
    CMS::call('CONFIG')->setSection('avatar', $config);

    $config = [];
    $config['db_size']        = empty($REQUEST['db_size'])        ? 100  : (int) $REQUEST['db_size'];
    $config['per_page']       = empty($REQUEST['per_page'])       ? 30   : (int) $REQUEST['per_page'];
    $config['message_length'] = empty($REQUEST['message_length']) ? 4000 : (int) $REQUEST['message_length'];
    CMS::call('CONFIG')->setSection('pm', $config);

    $config = [];
    $config['message_length'] = empty($REQUEST['message_length']) ? 4000 : (int) $REQUEST['message_length'];
    $config['db_size']        = empty($REQUEST['db_size'])        ? 100  : (int) $REQUEST['db_size'];
    CMS::call('CONFIG')->setSection('feedback', $config);
    if (CMS::call('CONFIG')->save())
         echo SYSTEM::showMessage('Configuration saved');
    else echo SYSTEM::showError('Cannot save file'.' config.ini');
}

$config  = CONFIG::getSection('user');
$config += CONFIG::getSection('avatar');
$config += CONFIG::getSection('pm');
$config += CONFIG::getSection('feedback');

$TPL = new TEMPLATE(__DIR__.DS.'config.tpl');
$TPL->set($config);
echo $TPL->parse();

