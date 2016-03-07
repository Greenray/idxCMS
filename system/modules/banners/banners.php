<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module BANNERS

if (!defined('idxCMS')) die();

$banners = GetFilesList(BANNERS);

if (!empty($banners)) {
    $output = [];

    foreach ($banners as $i => $banner) {
        $output[$i]['text'] = CMS::call('PARSER')->parseText(file_get_contents(BANNERS.$banner));
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'banners.tpl');
    $TEMPLATE->set('banners', $output);
    SYSTEM::defineWindow('Banners', $TEMPLATE->parse());
}
