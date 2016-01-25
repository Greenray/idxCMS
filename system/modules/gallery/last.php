<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();

if (!empty($images)) {
    $images = CMS::call('GALLERY')->getLastItems($images);

    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set('items', $images);
    SYSTEM::defineWindow('Gallery updates', $TPL->parse());
}
