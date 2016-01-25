<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module POLLS

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
