<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module APHORISMS

if (!defined('idxCMS')) die();

/** Storage of aphorisms */
define('APHORISMS', CONTENT.'aphorisms'.DS);

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Aphorisms'] = 'Афоризмы';
        $LANG['def']['Left']      = 'Влево';
        $LANG['def']['Right']     = 'Вправо';
        break;

    case 'ua':
        $LANG['def']['Aphorisms'] = 'Афоризми';
        $LANG['def']['Left']      = 'Влево';
        $LANG['def']['Right']     = 'Вправо';
        break;

    case 'by':
        $LANG['def']['Aphorisms'] = 'Афарызмы';
        $LANG['def']['Left']      = 'Влево';
        $LANG['def']['Right']     = 'Вправо';
        break;
}

SYSTEM::registerModule('aphorisms', 'Aphorisms', 'box');
