<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE APHORISMS - INITIALIZATION

if (!defined('idxCMS')) die();

define('APHORISMS', CONTENT.'aphorisms'.DS);

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Aphorisms'] = 'Афоризмы';
        $LANG['def']['Next'] = 'Следующий';
        $LANG['def']['Previous'] = 'Предыдущий';
        break;
    case 'ua':
        $LANG['def']['Aphorisms'] = 'Афоризми';
        $LANG['def']['Next'] = 'Наступний';
        $LANG['def']['Previous'] = 'Попередній';
        break;
    case 'by':
        $LANG['def']['Aphorisms'] = 'Афарызмы';
        $LANG['def']['Next'] = 'Наступны';
        $LANG['def']['Previous'] = 'Папярэдні';
        break;
}

SYSTEM::registerModule('aphorisms', 'Aphorisms', 'box');
?>