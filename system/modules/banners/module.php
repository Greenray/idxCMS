<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE BANNERS - INITIALIZATION

if (!defined('idxCMS')) die();

define('BANNERS', CONTENT.'banners'.DS);

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Banners'] = 'Банеры';
        break;
    case 'ua':
        $LANG['def']['Banners'] = 'Банери';
        break;
    case 'by':
        $LANG['def']['Banners'] = 'Банеры';
        break;
}

SYSTEM::registerModule('banners', 'Banners', 'box');
?>