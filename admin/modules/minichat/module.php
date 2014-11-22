<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - MINICHAT - INITIALIZATION

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
?>