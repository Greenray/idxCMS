<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();

if (!empty($images)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $items = CMS::call('GALLERY')->getLastItems($images);

    $images = $items;
    foreach($items as $id => $item) {
        $items[$id]['image'] = CONTENT.'gallery'.DS.$item['section'].DS.$item['category'].DS.$item['id'].DS.$item['image'].'.jpg';
    }
    $TPL->set('items', $items);
    SYSTEM::defineWindow('Last photos', $TPL->parse());
}
