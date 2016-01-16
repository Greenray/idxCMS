<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module CATALOGS: Last items

if (!defined('idxCMS')) die();

$items = CMS::call('CATALOGS')->getSectionsLastItems();

if (!empty($items)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set(CMS::call('CATALOGS')->getLastItems($items));
    SYSTEM::defineWindow('Catalogs updates', $TPL->parse());
}
