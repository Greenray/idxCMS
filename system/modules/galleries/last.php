<?php
# idxCMS Flat Files Content Management Sysytem
# Module Galleries
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERIES')->getSections();
$last     = CMS::call('GALLERIES')->getSectionsLastItems();

if (!empty($last)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Updates'), $TPL->parse(CMS::call('GALLERIES')->getLastItems($last)));
}
