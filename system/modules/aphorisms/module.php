<?php
/** Aphorizms.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/aphorizms/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Aphorizms
 * @overview  Displays aphorisms or any other pre-prepared text.
 *            Used a random selection from the list.
 *            Aphorisms database is a plain text file. One string is one aphorism.
 *            Aphorisms are displayed randomly. Each locale has its file named as "locale".txt
 */

if (!defined('idxCMS')) die();

/** Data storage for aphorizms */
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
