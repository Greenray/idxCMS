<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: User panel

if (!defined('idxCMS')) die();

$info = ['', ''];

if (USER::$logged_in) {
    $PM   = new MESSAGE(PM_DATA, USER::getUser('user'));
    $info = $PM->checkNewMessages();
    unset($PM);
}

$TEMPLATE = new TEMPLATE(__DIR__.DS.'panel.tpl');
$TEMPLATE->set('logged_in',   USER::$logged_in);
$TEMPLATE->set('user',        USER::getUser('nick'));
$TEMPLATE->set('admin',       USER::$root);
$TEMPLATE->set('mess_new',    $info[0]);
$TEMPLATE->set('mess_info',   $info[1]);
$TEMPLATE->set('captcha', ShowCaptcha());
$TEMPLATE->set('allow_skins', CONFIG::getValue('main', 'allow_skin'));
$TEMPLATE->set('select_skin', SelectPoint(
    'skin',
    AdvScanDir(SKINS, '', 'dir', FALSE, ['images']),
    SYSTEM::get('skin'),
    'onchange="document.forms[\'skin_select\'].submit()" title="'.__('Skin').'"'
));
$TEMPLATE->set('allow_languages', CONFIG::getValue('main', 'allow_language'));
$TEMPLATE->set('select_lang', SelectPoint(
    'language',
    SYSTEM::get('languages'),
    SYSTEM::get('language'),
    'onchange="document.forms[\'lang_select\'].submit()" title="'.__('Language').'"'
));

SYSTEM::defineWindow('User panel', $TEMPLATE->parse());
