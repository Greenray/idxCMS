<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
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