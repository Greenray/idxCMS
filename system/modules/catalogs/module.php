<?php
/** Catalogs of files, links, etc.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/catalogs/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Catalogs
 * @overview  Catalogs of files, links, etc.
 *            Catalogs database is similar to posts database.
 */

if (!defined('idxCMS')) die();

/** Data storage for catalogs */
define('CATALOGS', CONTENT.'catalogs'.DS);

require SYS.'catalogs.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Download'] = 'Скачать';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Ссылки';
        $LANG['def']['Size'] = 'Размер';
        $LANG['def']['Transitions'] = 'Переходов';
        $LANG['def']['Updates'] = 'Обновления';
        break;
    case 'ua':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Download'] = 'Скачати';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлi';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Посилання';
        $LANG['def']['Size'] = 'Розмір';
        $LANG['def']['Transitions'] = 'Переходів';
        $LANG['def']['Updates'] = 'Оновлення';
        break;
    case 'by':
        $LANG['def']['Catalogs'] = 'Каталогі';
        $LANG['def']['Download'] = 'Запампаваць';
        $LANG['def']['Downloads'] = 'запампаваны';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перайсці';
        $LANG['def']['Links'] = 'Спасылкі';
        $LANG['def']['Size'] = 'Памер';
        $LANG['def']['Transitions'] = 'Пераходаў';
        $LANG['def']['Updates'] = 'Абнаўленні';
        break;
}

SYSTEM::registerModule('catalogs', 'Catalogs', 'main');
SYSTEM::registerModule('catalogs.last', 'Updates', 'box');
USER::setSystemRights(['catalogs' => __('Catalogs').': '.__('Moderator')]);
SYSTEM::registerMainMenu('catalogs');
SYSTEM::registerSiteMap('catalogs');
SYSTEM::registerSearch('catalogs');
