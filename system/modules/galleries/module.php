<?php
# idxCMS Flat Files Content Management Sysytem
# Module Galleries
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

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
