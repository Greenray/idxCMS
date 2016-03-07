<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module CATALOGS: Last items

if (!defined('idxCMS')) die();

$items = CMS::call('CATALOGS')->getSectionsLastItems();

if (!empty($items)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TEMPLATE->set('items', CMS::call('CATALOGS')->getLastItems($items));
    SYSTEM::defineWindow('Catalogs updates', $TEMPLATE->parse());
}
