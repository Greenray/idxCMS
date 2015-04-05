<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

/** Data store for articles and news */
define('POSTS', CONTENT.'posts'.DS);

require SYS.'posts.class.php';
require SYS.'calendar.class.php';

SYSTEM::registerModule('posts',          'Posts',          'main', 'system');
SYSTEM::registerModule('posts.post',     'Posting form',   'main', 'system');
SYSTEM::registerModule('posts.calendar', 'Posts calendar', 'box',  'system');
SYSTEM::registerModule('posts.last',     'Last posts',     'box');
SYSTEM::registerModule('posts.news',     'Last news',      'box');
SYSTEM::registerModule('posts.print',    'Version for printer', 'plugin');
SYSTEM::registerSearch('posts');
SYSTEM::registerSiteMap('posts');
SYSTEM::registerMainMenu('posts');
USER::setSystemRights(array('posts' => __('Posts').': '.__('Moderator')));

$sections =  CMS::call('POSTS')->getSections();

if (!empty($sections)) {
    # Register RSS feeds for posts sections (ex. drafts).
    if (!empty($sections['drafts']))  unset($sections['drafts']);
    if (!empty($sections['archive'])) unset($sections['archive']);
    foreach ($sections as $id => $section) {
        if ($section['access'] === 0) {
            SYSTEM::registerFeed(
                'posts@'.$id,
                 $section['title'],
                __('RSS for section').' '.$section['title'],
                'posts'
            );
        }
    }
}
