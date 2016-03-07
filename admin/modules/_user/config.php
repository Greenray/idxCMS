<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Users configuration.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['flood']       = empty($REQUEST['flood'])       ? 120 : $REQUEST['flood'];
    $config['nick_length'] = empty($REQUEST['nick_length']) ? 1   : $REQUEST['nick_length'];
    $config['timeout']     = empty($REQUEST['timeout'])     ? 300 : $REQUEST['timeout'];
    CMS::call('CONFIG')->setSection('user', $config);

    $config = [];
    $config['width']  = empty($REQUEST['width'])  ? 70    : $REQUEST['width'];
    $config['height'] = empty($REQUEST['height']) ? 70    : $REQUEST['height'];
    $config['size']   = empty($REQUEST['size'])   ? 50000 : $REQUEST['size'];
    CMS::call('CONFIG')->setSection('avatar', $config);

    $config = [];
    $config['db_size']        = empty($REQUEST['db_size'])        ? 100  : $REQUEST['db_size'];
    $config['per_page']       = empty($REQUEST['per_page'])       ? 30   : $REQUEST['per_page'];
    $config['message_length'] = empty($REQUEST['message_length']) ? 4000 : $REQUEST['message_length'];
    CMS::call('CONFIG')->setSection('pm', $config);

    $config = [];
    $config['message_length'] = empty($REQUEST['message_length']) ? 4000 : $REQUEST['message_length'];
    $config['db_size']        = empty($REQUEST['db_size'])        ? 100  : $REQUEST['db_size'];
    CMS::call('CONFIG')->setSection('feedback', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration has been saved');
    else ShowError('Cannot save file'.' config.ini');
}

$config  = CONFIG::getSection('user');
$config += CONFIG::getSection('avatar');
$config += CONFIG::getSection('pm');
$config += CONFIG::getSection('feedback');

$TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
$TEMPLATE->set($config);
echo $TEMPLATE->parse();

