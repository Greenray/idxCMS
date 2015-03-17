<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Aphorizms
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Select file'] = 'Выбирите файл';
        break;
    case 'ua':
        $LANG['def']['Select file'] = 'Оберіть файл';
        break;
    case 'by':
        $LANG['def']['Select file'] = 'Выбіраючы файл';
        break;
}
$MODULES[$module][0] = __('Aphorisms');
$MODULES[$module][1]['aphorisms'] = __('Aphorisms');
