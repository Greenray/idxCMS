<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module "MENU: Simple menu

if (!defined('idxCMS')) die();

$data = json_decode(file_get_contents(CONTENT.'menu'), TRUE);

$output = [];

foreach ($data as $module => $menu) {
    $output[$module]['name'] = $menu['name'];
    $output[$module]['desc'] = $menu['desc'];
    $output[$module]['link'] = $menu['link'];
}

$TEMPLATE = new TEMPLATE(__DIR__.DS.'simple.tpl');
$TEMPLATE->set('menus', $output);

SYSTEM::defineWindow('Simple menu', $TEMPLATE->parse());
