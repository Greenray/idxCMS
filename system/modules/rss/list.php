<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module RSS

if (!defined('idxCMS')) die();

if (CONFIG::getValue('enabled', 'rss')) {
    $feeds  = SYSTEM::get('feeds');
    $output = [];
    foreach ($feeds as $mod => $feed) {
        list ($module, $section) = explode('@', $mod);
        $obj = strtoupper($module);
        $sections = CMS::call($obj)->getSections();
        $data['module']  = $mod;
        $data['section'] = $section;
        $data['feed']    = $feed[0];
        $data['categories'] = CMS::call($obj)->getCategories($section);
        foreach ($data['categories'] as $id => $category) {
            $data['categories'][$id]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
            $data['categories'][$id]['link'] = str_replace(MODULE, MODULE.'rss&amp;m=', $category['link']);
        }
        $output['feeds'][] = $data;
    }
    SYSTEM::set('pagename', __('RSS feeds list'));
    $TPL = new TEMPLATE(__DIR__.DS.'list.tpl');
    $TPL->set($output);
    SYSTEM::defineWindow('RSS feeds', $TPL->parse());
} else {
    SYSTEM::defineWindow('RSS feeds', __('RSS feeds are off'));
}
