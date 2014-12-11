<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GENERAL CONFIGURATION, BACKUP and FILEMANAGER

if (!defined('idxADMIN')) die();

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Executing']      = 'Выполнение';
        $LANG['def']['Filemanager']    = 'Менеджер файлов';
        $LANG['def']['Group']          = 'Группа';
        $LANG['def']['Make directory'] = 'Создать каталог';
        $LANG['def']['Other']          = 'Остальные';
        $LANG['def']['Owner']          = 'Владелец';
        $LANG['def']['Reading']        = 'Чтение';
        $LANG['def']['Time']           = 'Время';
        $LANG['def']['View']           = 'Просмотр';
        $LANG['def']['Writing']        = 'Запись';
        break;
    case 'ua':
        $LANG['def']['Executing']      = 'Виконання';
        $LANG['def']['Filemanager']    = 'Менеджер файлів';
        $LANG['def']['Group']          = 'Група';
        $LANG['def']['Make directory'] = 'Створити каталог';
        $LANG['def']['Other']          = 'Решта';
        $LANG['def']['Owner']          = 'Власник';
        $LANG['def']['Reading']        = 'Читання';
        $LANG['def']['Time']           = 'Час';
        $LANG['def']['View']           = 'Перегляд';
        $LANG['def']['Writing']        = 'Запис';
        break;
    case 'by':
        $LANG['def']['Executing']      = 'Выкананне';
        $LANG['def']['Filemanager']    = 'Мэнэджэр файлаў';
        $LANG['def']['Group']          = 'Група';
        $LANG['def']['Make directory'] = 'Стварыць каталог';
        $LANG['def']['Other']          = 'Астатнія';
        $LANG['def']['Owner']          = 'Уладальнік';
        $LANG['def']['Reading']        = 'Чытанне';
        $LANG['def']['Time']           = 'Час';
        $LANG['def']['View']           = 'Прагляд';
        $LANG['def']['Writing']        = 'Запіс';
        break;
}
$MODULES[$module][0] = __('General options');
$MODULES[$module][1]['config']      = __('Site configuration');
$MODULES[$module][1]['backup']      = __('Backup');
$MODULES[$module][1]['filemanager'] = __('Filemanager');
