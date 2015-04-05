<?php
# idxCMS Flat Files Content Management Sysytem
# Module Forum
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$sections = CMS::call('FORUM')->getSections();

# Get last topics
$topics = CMS::call('FORUM')->getSectionsLastItems();
if (!empty($topics)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Last topics'), $TPL->parse(CMS::call('FORUM')->getLastItems($topics)));
}
