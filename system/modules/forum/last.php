<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module FORUM: Last topics

if (!defined('idxCMS')) die();

$topics = CMS::call('FORUM')->getSectionsLastItems();

if (!empty($topics)) {
    $TPL = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TPL->set(CMS::call('FORUM')->getLastItems($topics));
    SYSTEM::defineWindow('Last topics', $TPL->parse());
}
