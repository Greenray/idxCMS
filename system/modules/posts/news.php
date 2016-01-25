<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module POSTS: News

if (!defined('idxCMS')) die();

$categories = CMS::call('POSTS')->getCategories('news');
$output     = CMS::call('POSTS')->getCategoryLastItems('d F Y H:i:s');

if (!empty($output)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set($output);
    SYSTEM::defineWindow('Last news', $TPL->parse());
}
