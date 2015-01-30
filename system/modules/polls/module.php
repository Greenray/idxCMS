<?php
# idxCMS Flat Files Content Management Sysytem

/** Polls.
 * Module registration.
 * @file      system/modules/polls/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Polls
 */
if (!defined('idxCMS')) die();

require SYS.'polls.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Poll'] = 'Голосование';
        $LANG['def']['Polls'] = 'Голосования';
        $LANG['def']['Polls archive'] = 'Архив голосований';
        $LANG['def']['Total votes'] = 'Всего голосов';
        break;
    case 'ua':
        $LANG['def']['Poll'] = 'Голосування';
        $LANG['def']['Polls'] = 'Голосування';
        $LANG['def']['Polls archive'] = 'Архів голосувань';
        $LANG['def']['Total votes'] = 'Всього голосів';
        break;
    case 'by':
        $LANG['def']['Poll'] = 'Галасаванне';
        $LANG['def']['Polls'] = 'Галасаванні';
        $LANG['def']['Polls archive'] = 'Архіў галасаванняў';
        $LANG['def']['Total votes'] = 'Усяго галасоў';
        break;
}

SYSTEM::registerModule('polls.archive', 'Polls archive', 'main');
SYSTEM::registerModule('polls', 'Poll', 'box');
