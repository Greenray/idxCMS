<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module: GALLERY

if (!defined('idxCMS')) die();

/** Data storage for gallery */
define('GALLERY', CONTENT.'gallery'.DS);

require SYS.'gallery.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Gallery'] = 'Галерея';
        $LANG['def']['Gallery preview'] = 'Предпросмотр галереи';
        $LANG['def']['Gallery updates'] = 'Обновления галереи';
        $LANG['def']['Random image'] = 'Случайное изображение';
        break;

    case 'ua':
        $LANG['def']['Gallery'] = 'Галерея';
        $LANG['def']['Gallery preview'] = 'Перегляд галереї';
        $LANG['def']['Gallery updates'] = 'Оновлення галереї';
        $LANG['def']['Random image'] = 'Випадкове зображення';
        break;

    case 'by':
        $LANG['def']['Gallery'] = 'Галерэя';
        $LANG['def']['Gallery preview'] = 'Прадпрагляд галерэі';
        $LANG['def']['Gallery updates'] = 'Абнаўлення галерэі';
        $LANG['def']['Random image'] = 'Выпадковае малюнак';
        break;
}

SYSTEM::registerModule('gallery', 'Gallery', 'main');
SYSTEM::registerModule('gallery.randimage', 'Random image', 'box');
SYSTEM::registerModule('gallery.last', 'Gallery updates', 'box');
SYSTEM::registerModule('gallery.preview', 'Gallery preview', 'box');

SYSTEM::registerMainMenu('gallery');
SYSTEM::registerSiteMap('gallery');
SYSTEM::registerSearch('gallery');

USER::setSystemRights(['gallery' => __('Gallery').': '.__('Moderator')]);
