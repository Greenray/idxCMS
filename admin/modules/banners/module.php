<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - BANNERS - INITIALIZATION

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
?>