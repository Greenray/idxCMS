<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: News

if (!defined('idxCMS')) die();

$categories = CMS::call('POSTS')->getCategories('news');
$output     = CMS::call('POSTS')->getCategoryLastItems('d F Y H:i:s');

if (!empty($output)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TEMPLATE->set($output);
    SYSTEM::defineWindow('Last news', $TEMPLATE->parse());
}
