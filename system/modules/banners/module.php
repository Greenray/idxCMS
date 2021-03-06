<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module BANNERS

if (!defined('idxCMS')) die();

/** Storage of banners */
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
