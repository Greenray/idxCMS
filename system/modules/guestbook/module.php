<?php
# idxCMS Flat Files Content Management Sysytem
# Module Guestbook
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

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
USER::setSystemRights(array('guestbook' => __('Guestbook').': '.__('Moderator')));
SYSTEM::registerMainMenu('guestbook');
