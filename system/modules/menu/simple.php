<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$TPL    = new TEMPLATE(dirname(__FILE__).DS.'simple.tpl');
$output = '<div id="simple-menu"><ul class="center">';

foreach ($data as $module => $menu) {
    $output .= $TPL->parse($menu);
}

$output .= '</ul></div>';
ShowWindow(__('Simple menu'), $output);
