<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Banners.

if (!defined('idxADMIN') || !USER::$root) die();
#
# Save banners
#
if (!empty($REQUEST['save'])) {
    $i = 1;
    foreach ($REQUEST['text'] as $banner) {
        if (!empty($banner)) {
            if (!file_put_contents(BANNERS.$i, $banner)) {
                echo SYSTEM::showError('Cannot save file'.' '.BANNERS.$i);
            }
            ++$i;
        }
    }
}

$banners = GetFilesList(BANNERS);
$i = 0;
$output = [];

foreach ($banners as $banner) {
    ++$i;
    $text = file_get_contents(BANNERS.$banner);
    $output['banner'][$i]['id']      = $i;
    $output['banner'][$i]['text']    = $text;
    $output['banner'][$i]['view']    = CMS::call('PARSER')->parseText($text);
    $output['banner'][$i]['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.'.$i);
}

$i += 1;
$output['banner'][$i]['id']      = $i;
$output['banner'][$i]['text']    = '';
$output['banner'][$i]['view']    = '';
$output['banner'][$i]['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.'.$i);

$TPL = new TEMPLATE(__DIR__.DS.'banners.tpl');
$TPL->set($output);
echo $TPL->parse();
