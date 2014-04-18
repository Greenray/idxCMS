<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - LAST TOPICS

if (!defined('idxCMS')) die();

$sections = CMS::call('FORUM')->getSections();
# Get last topics
$topics = CMS::call('FORUM')->getSectionsLastItems();
if (!empty($topics)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(
        __('Last topics'), 
        $TPL->parse(
            CMS::call('FORUM')->getLastItems($topics)
        )
    );
}
?>