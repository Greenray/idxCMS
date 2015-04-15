<?php
/** Banners.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/banners/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Banners
 * @overview  Displays banners or any other pre-prepared text.
 *            Banner is a plain file with bbcodes.
 */

if (!defined('idxCMS')) die();

/** Data storage fo banners */
define('BANNERS', CONTENT.'banners'.DS);

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Banners'] = 'Банеры';
        break;
    case 'ua':
        $LANG['def']['Banners'] = 'Банери';
        break;
    case 'by':
        $LANG['def']['Banners'] = 'Банеры';
        break;
}

# Register module as box
SYSTEM::registerModule('banners', 'Banners', 'box');
