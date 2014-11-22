<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE MINICHAT - INITIALIZATION

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
USER::setSystemRights(array('minichat' => __('Minichat').': '.__('Moderator')));
?>