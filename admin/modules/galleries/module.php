<?php
# idxCMS Flat Files Content Management Sysytem v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Gallery.

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Images in panel'] = 'Изображений в боковой панели';
        $LANG['def']['Images per page'] = 'Изображений на страницу';
        $LANG['def']['New image'] = 'Новое изображение';
        break;
    case 'ua':
        $LANG['def']['Images in panel'] = 'Зображень у бічній панелі';
        $LANG['def']['Images per page'] = 'Зображень на сторінку';
        $LANG['def']['New image'] = 'Новое изображение';
        break;
    case 'by':
        $LANG['def']['Images in panel'] = 'Малюнкаў у бакавой панэлі';
        $LANG['def']['Images per page'] = 'Малюнкаў на старонку';
        $LANG['def']['New image'] = 'Новое изображение';
        break;
}
$MODULES[$module][0] = 'Gallery';
$MODULES[$module][1]['config']     = 'Configuration';
$MODULES[$module][1]['sections']   = 'Sections';
$MODULES[$module][1]['categories'] = 'Categories';
