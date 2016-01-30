<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: Last topics

if (!defined('idxCMS')) die();

$topics = CMS::call('FORUM')->getSectionsLastItems();

if (!empty($topics)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set('items', CMS::call('FORUM')->getLastItems($topics));
    SYSTEM::defineWindow('Last topics', $TPL->parse());
}
