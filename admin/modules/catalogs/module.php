<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Catalogs management.

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['New file'] = 'Новый файл';
        $LANG['def']['New link'] = 'Новая ссылка';
        break;
    case 'ua':
        $LANG['def']['New file'] = 'Новий файл';
        $LANG['def']['New link'] = 'Нова посилання';
        break;
    case 'by':
        $LANG['def']['New link'] = 'Новы файл';
        $LANG['def']['New link'] = 'Новая спасылка';
        break;
}

$MODULES[$module][0] = __('Catalogs');
$MODULES[$module][1]['config']     = __('Configuration');
$MODULES[$module][1]['sections']   = __('Sections');
$MODULES[$module][1]['categories'] = __('Categories');
