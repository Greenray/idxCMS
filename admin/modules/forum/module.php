<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Forum.

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Max reply length'] = 'Max размер ответа';
        $LANG['def']['Replies per page'] = 'Ответов на страницу';
        $LANG['def']['Topics per page']  = 'Тем на страницу';
        break;
    case 'ua':
        $LANG['def']['Max reply length'] = 'Max розмір відповіді';
        $LANG['def']['Replies per page'] = 'Відповідей на сторінку';
        $LANG['def']['Topics per page']  = 'Тем на сторінку';
        break;
    case 'by':
        $LANG['def']['Max reply length'] = 'Max памер адказу';
        $LANG['def']['Replies per page'] = 'Адказаў на старонку';
        $LANG['def']['Topics per page']  = 'Тым на старонку';
        break;
}
$MODULES[$module][0] = __('Forum');
$MODULES[$module][1]['config']     = __('Configuration');
$MODULES[$module][1]['sections']   = __('Sections');
$MODULES[$module][1]['categories'] = __('Categories');
