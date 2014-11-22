<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE SITEMAP - INITIALIZATION

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
?>