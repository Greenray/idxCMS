<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module GALLERY: Last images.

if (!defined('idxCMS')) die();

$images = CMS::call('GALLERY')->getSectionsLastItems();

if (!empty($images)) {
    $images = CMS::call('GALLERY')->getLastItems($images);

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TEMPLATE->set('items', $images);
    SYSTEM::defineWindow('Gallery updates', $TEMPLATE->parse());
}
