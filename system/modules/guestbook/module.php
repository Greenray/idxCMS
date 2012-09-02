<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE GUESTBOOK - INITIALIZATION

if (!defined('idxCMS')) die();

function MenuGuestbook() {
    $menu['guestbook']['module'] = 'guestbook';
    $menu['guestbook']['link']   = MODULE.'guestbook';
    $menu['guestbook']['name']   = SYSTEM::$modules['guestbook']['title'];
    $menu['guestbook']['desc']   = '';
    $menu['guestbook']['icon']   = ICONS.'guestbook.png';
    return $menu;
}

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Guestbook'] = 'Гостевая книга';
        $LANG['def']['Message'] = 'Сообщение';
        break;
    case 'ua':
        $LANG['def']['Guestbook'] = 'Гостьова книга';
        $LANG['def']['Message'] = 'Повідомлення';
        break;
    case 'by':
        $LANG['def']['Guestbook'] = 'Гасцявая кніга';
        $LANG['def']['Message'] = 'Паведамленне';
        break;
}

SYSTEM::registerModule('guestbook', 'Guestbook', 'main');
USER::setSystemRights(array('guestbook' => __('Guestbook').': '.__('Moderator')));
SYSTEM::registerMainMenu('guestbook', 'MenuGuestbook');
?>