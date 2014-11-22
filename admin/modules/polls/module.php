<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POLLS - INITIALIZATION

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
?>