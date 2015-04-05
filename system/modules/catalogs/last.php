<?php
# idxCMS Flat Files Content Management Sysytem
# Module Catalogs
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$sections = CMS::call('CATALOGS')->getSections();
$items    = CMS::call('CATALOGS')->getSectionsLastItems($sections);

if (!empty($items)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(
        __('Updates'),
        $TPL->parse(CMS::call('CATALOGS')->getLastItems($items))
    );
}
