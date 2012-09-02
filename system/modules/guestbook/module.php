<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE GUESTBOOK - INITIALIZATION

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
?>