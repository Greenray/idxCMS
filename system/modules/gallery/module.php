<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module: GALLERY

if (!defined('idxCMS')) die();

/** Data storage for gallery */
define('GALLERY', CONTENT.'gallery'.DS);

require SYS.'gallery.class.php';

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Gallery'] = 'Галерея';
        $LANG['def']['Last photos'] = 'Последние фото';
        $LANG['def']['Random image'] = 'Случайное изображение';
        break;

    case 'ua':
        $LANG['def']['Gallery'] = 'Галерея';
        $LANG['def']['Last photos'] = 'Останні фото';
        $LANG['def']['Random image'] = 'Випадкове зображення';
        break;

    case 'by':
        $LANG['def']['Gallery'] = 'Галерэя';
        $LANG['def']['Last photos'] = 'Апошнія фота';
        $LANG['def']['Random image'] = 'Выпадковае малюнак';
        break;
}

SYSTEM::registerModule('gallery', 'Gallery', 'main');
SYSTEM::registerModule('gallery.randimage', 'Random image', 'box');
SYSTEM::registerModule('gallery.last', 'Gallery updates', 'box');

SYSTEM::registerMainMenu('gallery');
SYSTEM::registerSiteMap('gallery');
SYSTEM::registerSearch('gallery');

USER::setSystemRights(['gallery' => __('Gallery').': '.__('Moderator')]);
