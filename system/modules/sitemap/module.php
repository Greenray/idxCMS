<?php
# idxCMS Flat Files Content Management Sysytem
# Module Sitemap
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Sitemap'] = 'Карта сайта';
        break;
    case 'ua':
        $LANG['def']['Sitemap'] = 'Карта сайту';
        break;
    case 'by':
        $LANG['def']['Sitemap'] = 'Карта сайта';
        break;
}

SYSTEM::registerModule('sitemap', 'Sitemap', 'main');
