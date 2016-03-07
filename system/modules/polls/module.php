<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module POLLS

if (!defined('idxCMS')) die();

require SYS.'polls.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Poll'] = 'Голосование';
        $LANG['def']['Polls'] = 'Голосования';
        $LANG['def']['Polls archive'] = 'Архив голосований';
        $LANG['def']['Total votes'] = 'Всего голосов';
        $LANG['def']['You already voted in this poll'] = 'Вы уже проголосовали в этом опросе';
        break;

    case 'ua':
        $LANG['def']['Poll'] = 'Голосування';
        $LANG['def']['Polls'] = 'Голосування';
        $LANG['def']['Polls archive'] = 'Архів голосувань';
        $LANG['def']['Total votes'] = 'Всього голосів';
        $LANG['def']['You already voted in this poll'] = 'Ви вже проголосували в цьому опитуванні';
        break;

    case 'by':
        $LANG['def']['Poll'] = 'Галасаванне';
        $LANG['def']['Polls'] = 'Галасаванні';
        $LANG['def']['Polls archive'] = 'Архіў галасаванняў';
        $LANG['def']['Total votes'] = 'Усяго галасоў';
        $LANG['def']['You already voted in this poll'] = 'Вы ўжо прагаласавалі ў гэтым апытанні';
        break;
}

SYSTEM::registerModule('polls.archive', 'Polls archive', 'main');
SYSTEM::registerModule('polls', 'Poll', 'box');
