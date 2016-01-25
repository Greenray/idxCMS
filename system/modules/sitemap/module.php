<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module SITEMAP

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
