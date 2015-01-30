<?php
# idxCMS Flat Files Content Management Sysytem

/** Photo albums.
 * Module registration.
 * @file      system/modules/galleries/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Galleries
 */

if (!defined('idxCMS')) die();

/** Galleries data store */
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
USER::setSystemRights(array('galleries' => __('Galleries').': '.__('Moderator')));
SYSTEM::registerMainMenu('galleries');
SYSTEM::registerSiteMap('galleries');
SYSTEM::registerSearch('galleries');
