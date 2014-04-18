<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE MENU

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$points = array_keys($data);
$last   = end($points);
$class  = 'first';
$access = USER::getUser('access');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'menu.tpl');
$output = '<div id="menu"><ul class="menu menu-dropdown">';
foreach($data as $module => $menu) {
    $active = '';
    if ($module === $_SESSION['request']) {
        $active = 'active';
    }
    if ($module === $last) {
        $class = 'last';
    }
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
    if (!empty($menu['categories'])) {
        foreach ($menu['categories'] as $key => $category) {
            if ($category['access'] > $access) {
                unset($menu['categories'][$key]);
            }
        }
    }
    $menu['active'] = $active;
    $menu['class']  = $class;
    $class   = '';
    $output .= $TPL->parse($menu);
}
$output .= '</ul></div>';
ShowWindow(__('Menu'), $output);
?>