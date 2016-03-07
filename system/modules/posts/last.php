<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: Last posts

if (!defined('idxCMS')) die();

$sections = CMS::call('POSTS')->getSections();
unset($sections['drafts']);

$posts = CMS::call('POSTS')->getSectionsLastItems($sections);

if (!empty($posts)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TEMPLATE->set('items', CMS::call('POSTS')->getLastItems($posts));
    SYSTEM::defineWindow('Last posts',  $TEMPLATE->parse());
}
