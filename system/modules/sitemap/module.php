<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
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
