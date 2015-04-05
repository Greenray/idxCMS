<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Galleries
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Images per page'] = 'Изображений на страницу';
        break;
    case 'ua':
        $LANG['def']['Images per page'] = 'Зображень на сторінку';
        break;
    case 'by':
        $LANG['def']['Images per page'] = 'Малюнкаў на старонку';
        break;
}
$MODULES[$module][0] = __('Galleries');
$MODULES[$module][1]['config']     = __('Configuration');
$MODULES[$module][1]['sections']   = __('Sections');
$MODULES[$module][1]['categories'] = __('Categories');
