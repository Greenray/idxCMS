<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Banners
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

# Save banners
if (!empty($REQUEST['save'])) {
    $i = 1;
    foreach ($REQUEST['text'] as $banner) {
        if (!empty($banner)) {
            if (!file_put_contents(BANNERS.$i, $banner)) {
                ShowMessage(__('Cannot save file').' '.BANNERS.$i);
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

$TPL = new TEMPLATE(dirname(__FILE__).DS.'banners.tpl');
echo $TPL->parse($output);
