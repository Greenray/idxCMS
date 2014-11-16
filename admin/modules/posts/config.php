<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POSTS - CONFIGURATION

if (!defined('idxADMIN')) die();

# Save configuration
if (!empty($REQUEST['save'])) {
    $config = array();
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
?>