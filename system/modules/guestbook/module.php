<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module GUESTBOOK

if (!defined('idxCMS')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Guestbook'] = 'Гостевая книга';
        break;

    case 'ua':
        $LANG['def']['Guestbook'] = 'Гостьова книга';
        break;

    case 'by':
        $LANG['def']['Guestbook'] = 'Гасцявая кніга';
        break;
}

SYSTEM::registerModule('guestbook', 'Guestbook', 'main');
SYSTEM::registerMainMenu('guestbook');
USER::setSystemRights(['guestbook' => __('Guestbook').': '.__('Moderator')]);
