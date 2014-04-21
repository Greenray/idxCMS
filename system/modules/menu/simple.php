<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE MENU - SIMPLE MENU

if (!defined('idxCMS')) die();

$data    = GetUnserialized(CONTENT.'menu');
$TPL     = new TEMPLATE(dirname(__FILE__).DS.'simple.tpl');
$output  = '<div id="simple-menu"><ul class="simple-menu">';

foreach ($data as $module => $menu) {
    $output .= $TPL->parse($menu);
}

$output .= '</ul></div>';
ShowWindow(__('Simple menu'), $output);
?>