<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$output = [];

foreach ($data as $module => $menu) {
    $output['menu'][$module]['link'] = $menu['link'];
    $output['menu'][$module]['desc'] = $menu['desc'];
    $output['menu'][$module]['name'] = $menu['name'];
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'simple.tpl');
ShowWindow(__('Simple menu'), $TPL->parse($output));
