<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Banners.

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Banner code'] = 'Код банера';
        $LANG['def']['Banners editing'] = 'Редактирование банеров';
        $LANG['def']['New banner'] = 'Новый банер';
        break;
    case 'ua':
        $LANG['def']['Banner code'] = 'Код банера';
        $LANG['def']['Banners editing'] = 'Редагування банеров';
        $LANG['def']['New banner'] = 'Новий банер';
        break;
    case 'by':
        $LANG['def']['Banner code'] = 'Код банера';
        $LANG['def']['Banners editing'] = 'Рэдагаванне банераў';
        $LANG['def']['New banner'] = 'Новы банер';
        break;
}

$MODULES[$module][0] = __('Banners');
$MODULES[$module][1]['banners'] = __('Banners');
