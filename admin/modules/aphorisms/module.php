<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - APHORISMS - INITIALIZATION

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
?>
