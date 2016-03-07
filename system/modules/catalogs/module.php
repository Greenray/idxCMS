<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module CATALOGS

if (!defined('idxCMS')) die();

/** Storage of catalogs */
define('CATALOGS', CONTENT.'catalogs'.DS);

require SYS.'catalogs.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Catalogs updates'] = 'Обновления каталогов';
        $LANG['def']['Download'] = 'Скачать';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Ссылки';
        $LANG['def']['Size'] = 'Размер';
        $LANG['def']['Transitions'] = 'Переходов';
        break;

    case 'ua':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Catalogs updates'] = 'Оновлення каталогів';
        $LANG['def']['Download'] = 'Скачати';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлi';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Посилання';
        $LANG['def']['Size'] = 'Розмір';
        $LANG['def']['Transitions'] = 'Переходів';
        break;

    case 'by':
        $LANG['def']['Catalogs'] = 'Каталогі';
        $LANG['def']['Catalogs updates'] = 'Абнаўленне каталогаў';
        $LANG['def']['Download'] = 'Запампаваць';
        $LANG['def']['Downloads'] = 'запампаваны';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перайсці';
        $LANG['def']['Links'] = 'Спасылкі';
        $LANG['def']['Size'] = 'Памер';
        $LANG['def']['Transitions'] = 'Пераходаў';
        break;
}

SYSTEM::registerModule('catalogs',    'Catalogs',  'main');
SYSTEM::registerModule('catalogs.last', 'Catalogs updates', 'box');
SYSTEM::registerSearch('catalogs');
SYSTEM::registerMainMenu('catalogs');
SYSTEM::registerSiteMap('catalogs');

USER::setSystemRights(['catalogs' => __('Catalogs').': '.__('Moderator')]);
