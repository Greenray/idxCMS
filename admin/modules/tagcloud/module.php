<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - TAGCLOUD - INITIALIZATION

if (!defined('idxADMIN')) die();
switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Active'] = 'Активен';
        $LANG['def']['Background at the switched off transparency'] = 'Цвет фона при отключенной прозрачности';
        $LANG['def']['Color for gradient'] = 'Цвет градиента';
        $LANG['def']['Flash-object transparency'] = 'Прозрачность flash-объекта';
        $LANG['def']['For text mode'] = 'Для текстового режима';
        $LANG['def']['Generate a tags file'] = 'Сгенерировать файл тегов';
        $LANG['def']['In an ideal: 3/4 from width'] = 'В идеале: 3/4 от ширины';
        $LANG['def']['Leave a field empty for a multi-color mode'] = 'Оставьте поле пусты для многоцветного режима';
        $LANG['def']['Number of tags'] = 'Число тегов';
        $LANG['def']['Placing of references on sphere'] = 'Размещение ссылок на сфере';
        $LANG['def']['Quantity of mentions'] = 'Частота упоминания';
        $LANG['def']['Rotation speed of the sphere'] = 'Скорость вращения сферы';
        $LANG['def']['Speed in percentage of established by default'] = 'Скорость в %% от установленной по умолчанию';
        $LANG['def']['Tagcloud height'] = 'Высота облака тегов';
        $LANG['def']['Tagcloud width'] = 'Ширина облака тегов';
        $LANG['def']['Tags'] = 'Теги';
        $LANG['def']['Tags color'] = 'Цвет тегов';
        $LANG['def']['To place labels in regular intervals on the sphere area, differently - in a random way'] = 'Разместить ссылки равномерно на сфере, иначе - случайным образом';
        $LANG['def']['Update a tags file automatically'] = 'Обновлять теги автоматически';
        break;
    case 'ua':
        $LANG['def']['Active'] = 'Активний';
        $LANG['def']['Background at the switched off transparency'] = 'Колір фону при відключеній прозорості';
        $LANG['def']['Color for gradient'] = 'Колір градієнта';
        $LANG['def']['Flash-object transparency'] = 'Прозорість flash-об\'єкта';
        $LANG['def']['For text mode'] = 'Для текстового режиму';
        $LANG['def']['Generate a tags file'] = 'Згенерувати файл тегів';
        $LANG['def']['In an ideal: 3/4 from width'] = 'В ідеалі: 3/4 від ширини';
        $LANG['def']['Leave a field empty for a multi-color mode'] = 'Залиште поле порожні для багатоколірного режиму';
        $LANG['def']['Number of tags'] = 'Кількість тегів';
        $LANG['def']['Placing of references on sphere'] = 'Розміщення посилань на сфері';
        $LANG['def']['Quantity of mentions'] = 'Частота згадування';
        $LANG['def']['Rotation speed of the sphere'] = 'Швидкість обертання сфери';
        $LANG['def']['Speed ​​in percentage of established by default'] = 'Швидкість в%% від встановленої за замовчуванням';
        $LANG['def']['Tagcloud height'] = 'Висота хмари тегів';
        $LANG['def']['Tagcloud width'] = 'Ширина хмари тегів';
        $LANG['def']['Tags'] = 'Теги';
        $LANG['def']['Tags color'] = 'Колір тегів';
        $LANG['def']['To place labels in regular intervals on the sphere area, differently - in a random way'] = 'Розмістити посилання рівномірно на сфері, інакше - випадковим чином';
        $LANG['def']['Update a tags file automatically'] = 'Оновлювати теги автоматично';
        break;
    case 'by':
        $LANG['def']['Active'] = 'Актыўны';
        $LANG['def']['Background at the switched off transparency'] = 'Колер фону пры адключанай празрыстасці';
        $LANG['def']['Color for gradient'] = 'Колер градыенту';
        $LANG['def']['Flash-object transparency'] = 'Празрыстасць flash-аб\'екта';
        $LANG['def']['For text mode'] = 'Для тэкставага рэжыму';
        $LANG['def']['Generate a tags file'] = 'згенераваць файл тэгаў';
        $LANG['def']['In an ideal: 3/4 from width'] = 'У ідэале: 3/4 ад шырыні';
        $LANG['def']['Leave a field empty for a multi-color mode'] = 'Пакіньце поле пустыя для шматколернай рэжыму';
        $LANG['def']['Number of tags'] = 'Колькасць тэгаў';
        $LANG['def']['Placing of references on sphere'] = 'Размяшчэнне спасылак на сферы';
        $LANG['def']['Quantity of mentions'] = 'Частата згадкі';
        $LANG['def']['Rotation speed of the sphere'] = 'Хуткасць кручэння сферы';
        $LANG['def']['Speed ​​in percentage of established by default'] = 'Хуткасць у%% ад устаноўленай па змаўчанні';
        $LANG['def']['Tagcloud height'] = 'Вышыня воблака тэгаў';
        $LANG['def']['Tagcloud width'] = 'Шырыня воблака тэгаў';
        $LANG['def']['Tags'] = 'Тэгі';
        $LANG['def']['Tags color'] = 'Колер тэгаў';
        $LANG['def']['To place labels in regular intervals on the sphere area, differently - in a random way'] = 'Размясціць спасылкі раўнамерна на сферы, інакш - выпадковым чынам';
        $LANG['def']['Update a tags file automatically'] = 'Абнаўляць тэгі аўтаматычна';
        break;
}
$MODULES[$module][0] = __('Tagcloud');
$MODULES[$module][1]['config'] = __('Configuration');
?>