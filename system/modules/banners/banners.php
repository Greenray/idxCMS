<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module BANNERS

if (!defined('idxCMS')) die();

$banners = GetFilesList(BANNERS);

if (!empty($banners)) {
    $output = [];
    foreach ($banners as $i => $banner) {
        $output[$i]['text'] = CMS::call('PARSER')->parseText(file_get_contents(BANNERS.$banner));
    }
    $TPL = new TEMPLATE(__DIR__.DS.'banners.tpl');
    $TPL->set('banners', $output);
    #
    # Show banners box after module init
    #
    SYSTEM::defineWindow('Banners', $TPL->parse());
}
