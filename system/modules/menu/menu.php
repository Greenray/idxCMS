<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module MENU: Main menu

if (!defined('idxCMS')) die();

$TPL = new TEMPLATE(__DIR__.DS.'menu.tpl');
$TPL->set('menu', CMS::call('SYSTEM')->getMainMenu());
SYSTEM::defineWindow('Menu', $TPL->parse());
