<?php
# idxCMS Flat Files Content Management Sysytem
# Module Banners
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$banners = GetFilesList(BANNERS);

if (!empty($banners)) {
    $output = [];
    foreach ($banners as $i => $banner) {
        $output['banner'][$i]['text'] = CMS::call('PARSER')->parseText(file_get_contents(BANNERS.$banner));
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'banners.tpl');
    ShowWindow(__('Banners'), $TPL->parse($output));
}
