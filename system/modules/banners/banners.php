<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# Module BANNERS

if (!defined('idxCMS')) die();

$banners = GetFilesList(BANNERS);

if (!empty($banners)) {
    $output = array();
    foreach ($banners as $i => $banner) {
        $output['banner'][$i]['text'] = ParseText(file_get_contents(BANNERS.$banner));
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'banners.tpl');
    ShowWindow(__('Banners'), $TPL->parse($output));
}
?>