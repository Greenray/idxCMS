<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE GALLERIES - LAST TOPICS

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERIES')->getSections();
$stat = array();
$i = 1;
foreach ($sections as $section => $null) {
    $categories = CMS::call('GALLERIES')->getCategories($section);
    foreach ($categories as $key => $category) {
        $content = CMS::call('GALLERIES')->getContent($key);
        if (!empty($content)) {
            $stat[$i] = array_pop($content);
            $stat[$i]['link'] = $category['link'].ITEM.$stat[$i]['id'];
            $stat[$i]['path'] = $category['path'];
            ++$i;
        }
    }
} 

if (!empty($stat)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Updates'), $TPL->parse(array('items' => $stat)));
}
?>