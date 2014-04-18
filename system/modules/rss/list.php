<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE RSS - RSS LIST

if (!defined('idxCMS')) die();

if (CONFIG::getValue('enabled', 'rss')) {
    $feeds  = SYSTEM::get('feeds');
    $output = array();
    foreach ($feeds as $mod => $feed) {
        list ($module, $section) = explode('@', $mod);
        $obj = strtoupper($module);
        $sections = CMS::call($obj)->getSections();
        $data['module']  = $mod;
        $data['section'] = $section;
        $data['feed']    = $feed[0];
        $data['categories'] = CMS::call($obj)->getCategories($section);
        foreach ($data['categories'] as $id => $category) {
            $data['categories'][$id]['desc'] = ParseText($category['desc']);
            $data['categories'][$id]['link'] = str_replace(MODULE, MODULE.'rss&amp;m=', $category['link']);
        }
        $output['feed'][] = $data;
    }
    SYSTEM::set('pagename', __('RSS feeds list'));
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'list.tpl');
    ShowWindow(__('RSS feeds'), $TPL->parse($output));
} else ShowWindow(__('RSS feeds'), __('RSS feeds are off'));
?>