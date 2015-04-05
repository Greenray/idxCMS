<?php
# idxCMS Flat Files Content Management Sysytem
# Module Banners
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

/**Banners data store */
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

# Register module as box
SYSTEM::registerModule('banners', 'Banners', 'box');
