<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Minichat.

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
