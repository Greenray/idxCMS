<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$sections = CMS::call('POSTS')->getSections();
unset($sections['drafts']);

# Get last posts
$posts = CMS::call('POSTS')->getSectionsLastItems($sections);

if (!empty($posts)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(
        __('Last posts'),
        $TPL->parse(CMS::call('POSTS')->getLastItems($posts))
    );
}
