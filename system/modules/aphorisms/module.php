<?php
/**
 * @package    idxCMS
 * @subpackage MODULES
 * @file       system/modules/aphorisms/module.php
 * @version    2.3
 * @author     Victor Nabatov <greenray.spb@gmail.com>\n
 * @license    Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *             http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright  (c) 2011 - 2014 Victor Nabatov\n
 * @see        https://github.com/Greenray/idxCMS/system/modules/aphorisms/module.php
 */

if (!defined('idxCMS')) die();

/** Aphorizms data store */
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
