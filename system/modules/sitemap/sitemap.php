<?php
# idxCMS Flat Files Content Management Sysytem
# Module Sitemap
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$points = array_keys($data);
$access = USER::getUser('access');

$TPL    = new TEMPLATE(dirname(__FILE__).DS.'sitemap.tpl');
$output = '<div id="section"><ul class="level1">';

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
ShowWindow(__('Sitemap'), $output);
