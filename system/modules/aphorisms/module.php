<?php
# idxCMS Flat Files Content Management Sysytem
# Module Aphorizms
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

/** Aphorisms database.
 * This is a simple text file.
 * One string is one aphorism.
 * Aphorisms are displayed randomly.
 * Each locale has its file named as "locale".txt
 *
 * @package Aphorisms
 */
define('APHORISMS', CONTENT.'aphorisms'.DS);

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Aphorisms'] = 'Афоризмы';
        $LANG['def']['Next']      = 'Следующий';
        $LANG['def']['Previous']  = 'Предыдущий';
        break;

    case 'ua':
        $LANG['def']['Aphorisms'] = 'Афоризми';
        $LANG['def']['Next']      = 'Наступний';
        $LANG['def']['Previous']  = 'Попередній';
        break;

    case 'by':
        $LANG['def']['Aphorisms'] = 'Афарызмы';
        $LANG['def']['Next']      = 'Наступны';
        $LANG['def']['Previous']  = 'Папярэдні';
        break;
}

SYSTEM::registerModule('aphorisms', 'Aphorisms', 'box');
