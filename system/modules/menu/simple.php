<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module "MENU: Simple menu

if (!defined('idxCMS')) die();

$data = GetUnserialized(CONTENT.'menu');

$output = [];
foreach ($data as $module => $menu) {
    $output[$module]['name'] = $menu['name'];
    $output[$module]['desc'] = $menu['desc'];
    $output[$module]['link'] = $menu['link'];
}

$TPL = new TEMPLATE(__DIR__.DS.'simple.tpl');
$TPL->set('menus', $output);

SYSTEM::defineWindow('Simple menu', $TPL->parse());
