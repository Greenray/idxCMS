<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$categories = CMS::call('POSTS')->getCategories('news');
$output = CMS::call('POSTS')->getCategoryLastItems('d F Y H:i:s');

if (!empty($output)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Last news'), $TPL->parse($output));
}
