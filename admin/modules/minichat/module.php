<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Minichat
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Messages to show'] = 'Показывать сообщений';
         break;

    case 'ua':
        $LANG['def']['Messages to show'] = 'Показувати повідомлень';
        break;

    case 'by':
        $LANG['def']['Messages to show'] = 'Паказваць паведамленняў';
        break;
}
$MODULES[$module][0] = __('Minichat');
$MODULES[$module][1]['config'] = __('Minichat');
