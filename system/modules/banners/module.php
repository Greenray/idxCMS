<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE BANNERS - INITIALIZATION

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
