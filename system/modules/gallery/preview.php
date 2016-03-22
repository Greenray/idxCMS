<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();

if (!empty($images)) {
    $images = CMS::call('GALLERY')->getLastItems($images);
    $images = array_slice($images, 0, 3, TRUE);

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'preview.tpl');
    $TEMPLATE->set('images', $images);
    SYSTEM::defineWindow('Gallery preview', $TEMPLATE->parse());
}
