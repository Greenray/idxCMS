<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();
var_dump($images);
$a = CMS::call('GALLERY')->getLastItems($images);
var_dump($a);
if (!empty($images)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $items = CMS::call('GALLERY')->getLastItems($images);
    $img   = $items;
    foreach($items as $id => $item) {
        $img['items'][$id]['image'] = CONTENT;
    }
    $TPL->set(CMS::call('GALLERY')->getLastItems($images));

    SYSTEM::defineWindow('Last photos', $TPL->parse());
}
