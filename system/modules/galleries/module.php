<?php
/** Galleries.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/galleries/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Galleries
 * @overview  Galleries of photo or any other pictures.
 *            Database is similar to posts database.
 */

if (!defined('idxCMS')) die();

/** Data storage for galleries */
define('GALLERIES', CONTENT.'galleries'.DS);

require SYS.'galleries.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Galleries'] = 'Галереи';
        $LANG['def']['Random image'] = 'Случайное изображение';
        break;
    case 'ua':
        $LANG['def']['Galleries'] = 'Галереї';
        $LANG['def']['Random image'] = 'Випадкове зображення';
        break;
    case 'by':
        $LANG['def']['Galleries'] = 'Галерэі';
        $LANG['def']['Random image'] = 'Выпадковае малюнак';
        break;
}

SYSTEM::registerModule('galleries', 'Galleries', 'main');
SYSTEM::registerModule('galleries.randimage', 'Random image', 'box');
SYSTEM::registerModule('galleries.last', 'Updates', 'box');
USER::setSystemRights(['galleries' => __('Galleries').': '.__('Moderator')]);
SYSTEM::registerMainMenu('galleries');
SYSTEM::registerSiteMap('galleries');
SYSTEM::registerSearch('galleries');
