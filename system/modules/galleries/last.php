<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE GALLERIES - LAST TOPICS

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERIES')->getSections();

$last = CMS::call('GALLERIES')->getSectionsLastItems();

if (!empty($last)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Updates'), $TPL->parse(CMS::call('GALLERIES')->getLastItems($last)));
}
