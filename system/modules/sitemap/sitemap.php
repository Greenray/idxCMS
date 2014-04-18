<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE SITEMAP

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$points = array_keys($data);

$access = USER::getUser('access');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'sitemap.tpl');
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
    if (!empty($menu['categories'])) {
        foreach ($menu['categories'] as $key => $category) {
            if ($category['access'] > $access) {
                unset($menu['categories'][$key]);
            }
        }
    }
    $output .= $TPL->parse($menu);
}
$output .= '</ul></div>';
ShowWindow(__('Sitemap'), $output);
?>