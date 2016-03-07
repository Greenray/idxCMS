<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: Last topics

if (!defined('idxCMS')) die();

$topics = CMS::call('FORUM')->getSectionsLastItems();

if (!empty($topics)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'last.tpl');
    $TEMPLATE->set('items', CMS::call('FORUM')->getLastItems($topics));
    SYSTEM::defineWindow('Last topics', $TEMPLATE->parse());
}
