<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
