<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: Last posts

if (!defined('idxCMS')) die();

$sections = CMS::call('POSTS')->getSections();
unset($sections['drafts']);

$posts = CMS::call('POSTS')->getSectionsLastItems($sections);

if (!empty($posts)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set('items', CMS::call('POSTS')->getLastItems($posts));
    SYSTEM::defineWindow('Last posts',  $TPL->parse());
}
