<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module USER: User panel

if (!defined('idxCMS')) die();

$PM   = new MESSAGE(PM_DATA, USER::getUser('user'));
$info = $PM->checkNewMessages();
unset($PM);

$TPL = new TEMPLATE(__DIR__.DS.'panel.tpl');
$TPL->set('logged_in',   USER::$logged_in);
$TPL->set('user',        USER::getUser('nick'));
$TPL->set('admin',       USER::$root);
$TPL->set('mess_new',    $info[0]);
$TPL->set('mess_info',   $info[1]);
$TPL->set('captcha', ShowCaptcha());
$TPL->set('allow_skins', CONFIG::getValue('main', 'allow_skin'));
$TPL->set('select_skin', SelectPoint(
    'skin',
    AdvScanDir(SKINS, '', 'dir', FALSE, ['images']),
    SYSTEM::get('skin'),
    'onchange="document.forms[\'skin_select\'].submit()" title="'.__('Skin').'"'
));
$TPL->set('allow_languages', CONFIG::getValue('main', 'allow_language'));
$TPL->set('select_lang', SelectPoint(
    'language',
    SYSTEM::get('languages'),
    SYSTEM::get('language'),
    'onchange="document.forms[\'lang_select\'].submit()" title="'.__('Language').'"'
));

SYSTEM::defineWindow('User panel', $TPL->parse());
