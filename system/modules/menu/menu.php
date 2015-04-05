<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$data   = GetUnserialized(CONTENT.'menu');
$access = USER::getUser('access');
$output = [];

foreach($data as $module => $menu) {
    if (!empty($menu['sections'])) {
        $output['menu'][$module]['name'] = $menu['name'];
        $output['menu'][$module]['link'] = $menu['link'];
        foreach ($menu['sections'] as $id => $section) {
            if ($section['access'] > $access) {
                unset($menu['sections'][$id]);
            } else {
                $output['menu'][$module]['section'][$id]['title'] = $section['title'];
                $output['menu'][$module]['section'][$id]['link']  = $section['link'];
                $output['menu'][$module]['section'][$id]['width'] = $section['width'];
                if (!empty($section['categories'])) {
                    foreach ($section['categories'] as $key => $category) {
                        if ($category['access'] > $access) {
                            unset($menu['sections'][$id]['categories'][$key]);
                        } else {
                            $output['menu'][$module]['category'][$key]['title'] = $section['title'];
                            $output['menu'][$module]['category'][$key]['link']  = $section['link'];
                        }
                    }
                }
            }
        }
    } else {
        $output['menu'][$module]['name'] = $menu['name'];
        $output['menu'][$module]['link'] = $menu['link'];
    }
}

$output['menu']['doc']['name'] = 'idxCMS API';
$output['menu']['doc']['link'] = ROOT.'api'.DS.'index.html';

$TPL = new TEMPLATE(dirname(__FILE__).DS.'menu.tpl');
ShowWindow(__('Menu'), $TPL->parse($output));
