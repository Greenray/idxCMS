<?php
/**
 * Module POSTS: articles and news.
 *
 * @program   idxCMS: F lat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/modules/posts/module.php
 * @package   Posts
 * @overview  Articles and news which cfn be posted by registered users.
 *            The structure of the posts database:
 * <pre>
 * Sections index:
 * array(
 *   "section"
 *    array(
 *        "id"     - section ID
 *        "title"  - section title
 *        "desc"   - section description
 *        "access" - access level
 *        "link"   - link to section
 *        "path"   - path to section
 *        "categories"  - section categories
 *         array(
 *             "id"     - category ID
 *             "title"  - category title
 *             "desc"   - category description
 *             "access" - access level
 *             "link"   - link to category
 *             "path"   - path ro category
 *         )
 *    )
 * )
 *
 * Articles index:
 * array(
 *   "id"       - article ID
 *   "author"   - autor name
 *   "nick"     - author nicname
 *   "time"     - post time
 *   "views"    - number of views
 *   "comments" - article comments
 *   "title"    - article title
 *   "keywords" - article keywords
 *   "opened"   - is comments allowed?
 * )
 *
 * Comments index:
 * array(
 *   "id"       - comment ID
 *   "author"   - autor name
 *   "nick"     - author nicname
 *   "time"     - post time
 *   "text"     - comment text
 *   "ip"       - comment IP address
 *   "rate"     - comment rate
 * )
 * </pre>
 */

if (!defined('idxCMS')) die();

/** Data storage for articles and news */
 define('POSTS', CONTENT.'posts'.DS);

require SYS.'posts.class.php';
require SYS.'calendar.class.php';

SYSTEM::registerModule('posts',          'Posts',               'main', 'system');
SYSTEM::registerModule('posts.post',     'Posting form',        'main', 'system');
SYSTEM::registerModule('posts.calendar', 'Posts calendar',      'box',  'system');
SYSTEM::registerModule('posts.last',     'Last posts',          'box');
SYSTEM::registerModule('posts.news',     'Last news',           'box');
SYSTEM::registerModule('posts.print',    'Version for printer', 'plugin');

SYSTEM::registerSearch('posts');
SYSTEM::registerSiteMap('posts');
SYSTEM::registerMainMenu('posts');

USER::setSystemRights(['posts' => __('Posts').': '.__('Moderator')]);

$sections =  CMS::call('POSTS')->getSections();

if (!empty($sections)) {
    #
    # Register RSS feeds for posts sections (ex. drafts)
    #
    unset($sections['drafts']);

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
