<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Polls
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Answers'] = 'Ответы';
        $LANG['def']['New poll'] = 'Новое голосование';
        $LANG['def']['Question'] = 'Вопрос';
        $LANG['def']['Stop'] = 'Остановить';
        break;
    case 'ua':
        $LANG['def']['Answers'] = 'Відповіді';
        $LANG['def']['New poll'] = 'Нове голосування';
        $LANG['def']['Question'] = 'Питання';
        $LANG['def']['Stop'] = 'Зупинити';
        break;
    case 'by':
        $LANG['def']['Answers'] = 'Адказы';
        $LANG['def']['New poll'] = 'Новае галасаванне';
        $LANG['def']['Question'] = 'Пытанне';
        $LANG['def']['Stop'] = 'Спыніць';
        break;
}
$MODULES[$module][0] = __('Polls');
$MODULES[$module][1]['polls']   = __('Polls');
$MODULES[$module][1]['archive'] = __('Polls archive');
