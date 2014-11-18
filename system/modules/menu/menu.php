<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE MENU

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$access = USER::getUser('access');

$TPL    = new TEMPLATE(dirname(__FILE__).DS.'menu.tpl');
$output = '<div id="menu"><ul class="main_menu">';

foreach($data as $module => $menu) {
    if (!empty($menu['sections'])) {
        foreach ($menu['sections'] as $id => $section) {
            if ($section['access'] > $access) {
                unset($menu['sections'][$id]);
            } else {
                if (!empty($section['categories'])) {
                    foreach ($section['categories'] as $key => $category) {
                        if ($category['access'] > $access) {
                            unset($menu['sections'][$id]['categories'][$key]);
                        }
                    }
                }
            }
        }
    }
    $output .= $TPL->parse($menu);
}

$output .= '</ul></div>';
ShowWindow(__('Menu'), $output);
?>