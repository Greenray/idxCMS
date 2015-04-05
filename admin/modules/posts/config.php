<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Posts
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

# Save configuration
if (!empty($REQUEST['save'])) {
    $config = [];
    $config['description-length'] = empty($REQUEST['description-length']) ? 500  : (int) $REQUEST['description-length'];
    $config['comment-length']     = empty($REQUEST['comment-length'])     ? 4000 : (int) $REQUEST['comment-length'];
    $config['posts-per-page']     = empty($REQUEST['posts-per-page'])     ? 10   : (int) $REQUEST['posts-per-page'];
    $config['comments-per-page']  = empty($REQUEST['comments-per-page'])  ? 10   : (int) $REQUEST['comments-per-page'];
    CMS::call('CONFIG')->setSection('posts', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save file');
    }
}

$config = CONFIG::getSection('posts');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
