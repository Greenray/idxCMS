<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Publications and news configuration.

if (!defined('idxADMIN')) die();

if (!empty($REQUEST['save'])) {
    $config = [];
    $config['description_length'] = empty($REQUEST['description_length']) ? 500  : $REQUEST['description_length'];
    $config['message_length']     = empty($REQUEST['message_length'])     ? 4000 : $REQUEST['message_length'];
    $config['posts_per_page']     = empty($REQUEST['posts_per_page'])     ? 10   : $REQUEST['posts_per_page'];
    $config['comments_per_page']  = empty($REQUEST['comments_per_page'])  ? 10   : $REQUEST['comments_per_page'];
    CMS::call('CONFIG')->setSection('posts', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration has been saved');
    else ShowError('Cannot save file'.' config.ini');
}

$config = CONFIG::getSection('posts');

$TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
$TEMPLATE->set(CONFIG::getSection('posts'));
echo $TEMPLATE->parse();
