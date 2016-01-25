<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();

if (!empty($images)) {
    $images = CMS::call('GALLERY')->getLastItems($images);
    $images = array_slice($images, 0, 3, TRUE);

    $TPL = new TEMPLATE(__DIR__.DS.'preview.tpl');
    $TPL->set('images', $images);
    SYSTEM::defineWindow('Gallery preview', $TPL->parse());
}
