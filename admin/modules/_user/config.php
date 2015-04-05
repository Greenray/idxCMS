<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['flood']       = empty($REQUEST['flood'])       ? 120 : (int) $REQUEST['flood'];
    $config['nick-length'] = empty($REQUEST['nick-length']) ? 1   : (int) $REQUEST['nick-length'];
    $config['timeout']     = empty($REQUEST['timeout'])     ? 300 : (int) $REQUEST['timeout'];
    CMS::call('CONFIG')->setSection('user', $config);

    $config = [];
    $config['width']  = empty($REQUEST['width'])  ? 70    : (int) $REQUEST['width'];
    $config['height'] = empty($REQUEST['height']) ? 70    : (int) $REQUEST['height'];
    $config['size']   = empty($REQUEST['size'])   ? 50000 : (int) $REQUEST['size'];
    CMS::call('CONFIG')->setSection('avatar', $config);

    $config = [];
    $config['db-size']        = empty($REQUEST['db-size'])        ? 100  : (int) $REQUEST['db-size'];
    $config['per-page']       = empty($REQUEST['per-page'])       ? 30   : (int) $REQUEST['per-page'];
    $config['message-length'] = empty($REQUEST['message-length']) ? 2000 : (int) $REQUEST['message-length'];
    CMS::call('CONFIG')->setSection('pm', $config);

    $config = [];
    $config['email']          = $REQUEST['email'];
    $config['message-length'] = empty($REQUEST['message-length']) ? 2000 : (int) $REQUEST['message-length'];
    $config['db-size']        = empty($REQUEST['db-size'])        ? 100  : (int) $REQUEST['db-size'];
    CMS::call('CONFIG')->setSection('feedback', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save file');
    }
}

$config  = CONFIG::getSection('user');
$config += CONFIG::getSection('avatar');
$config += CONFIG::getSection('pm');
$config += CONFIG::getSection('feedback');

$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
