<?php
# idxCMS Flat Files Content Management Sysytem

/** RSS feeds.
 * Module registration.
 * @file      system/modules/rss/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   RSS
 */

if (!defined('idxCMS')) die();

require SYS.'rss_feeds.class.php';
require SYS.'rss_aggregator.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['RSS aggregator']         = 'RSS агрегатор';
        $LANG['def']['RSS feeds']              = 'Ленты RSS';
        $LANG['def']['RSS feeds are off']      = 'Ленты RSS отключены';
        $LANG['def']['RSS feeds list']         = 'Список RSS лент';
        $LANG['def']['Subscribe for all']      = 'Подписаться на все';
        $LANG['def']['Subscribe to RSS feeds'] = 'Подписка на ленты RSS';
        break;

    case 'ua':
        $LANG['def']['RSS aggregator']         = 'RSS агрегатор';
        $LANG['def']['RSS feeds']              = 'Стрічки RSS';
        $LANG['def']['RSS feeds are off']      = 'Стрічки RSS відключені';
        $LANG['def']['RSS feeds list']         = 'Список RSS стрічок';
        $LANG['def']['Subscribe for all']      = 'Підписатися на все';
        $LANG['def']['Subscribe to RSS feeds'] = 'Підписка на стрічки RSS';
        break;

    case 'by':
        $LANG['def']['RSS aggregator']         = 'RSS агрэгатар';
        $LANG['def']['RSS feeds']              = 'Стужкі RSS';
        $LANG['def']['RSS feeds are off']      = 'Стужкі RSS адключаныя';
        $LANG['def']['RSS feeds list']         = 'Спіс RSS стужак';
        $LANG['def']['Subscribe for all']      = 'Падпісацца на ўсе';
        $LANG['def']['Subscribe to RSS feeds'] = 'Падпіска на стужкі RSS';
        break;
}

SYSTEM::registerModule('rss',            'RSS feeds',      'box');
SYSTEM::registerModule('rss.list',       'RSS feeds list', 'main');
SYSTEM::registerModule('rss.aggregator', 'RSS aggregator', 'box');
