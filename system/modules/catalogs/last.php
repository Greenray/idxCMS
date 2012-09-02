<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - LAST TOPICS

if (!defined('idxCMS')) die();

$sections = CMS::call('CATALOGS')->getSections();
$items = CMS::call('CATALOGS')->getSectionsLastItems($sections);
if (!empty($items)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(
        __('Updates'), 
        $TPL->parse(
            CMS::call('CATALOGS')->getLastItems($items)
        )
    );
}
?>