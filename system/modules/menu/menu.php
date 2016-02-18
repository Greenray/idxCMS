<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module MENU: Main menu

if (!defined('idxCMS')) die();

$TEMPLATE = new TEMPLATE(__DIR__.DS.'menu.tpl');
$TEMPLATE->set('menu', CMS::call('SYSTEM')->getMainMenu());
SYSTEM::defineWindow('Menu', $TEMPLATE->parse());
