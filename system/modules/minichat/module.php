<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module MINICHAT

if (!defined('idxCMS')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Minichat'] = 'Миничат';
        break;

    case 'ua':
        $LANG['def']['Minichat'] = 'Мiнiчат';
        break;

    case 'by':
        $LANG['def']['Minichat'] = 'Миничат';
        break;
}

SYSTEM::registerModule('minichat', 'Minichat', 'box');
USER::setSystemRights(['minichat' => __('Minichat').': '.__('Moderator')]);
