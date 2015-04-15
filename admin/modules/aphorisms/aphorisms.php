<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Aphorizms
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    if (!empty($REQUEST['aph']) && !empty($REQUEST['file'])) {
        if (!file_put_contents(APHORISMS.$REQUEST['file'], $REQUEST['aph'])) {
            ShowMessage(__('Cannot save file').' '.$REQUEST['file']);
        }
    }
}

$aphorisms = array_values(GetFilesList(APHORISMS));

if (!empty($REQUEST['selected'])) {
    $output = [];
    if (in_array($REQUEST['selected'], $aphorisms)) {
        $output['file'] = $REQUEST['selected'];

        if     (file_exists(APHORISMS.$REQUEST['selected']))          $output['aph'] = file_get_contents(APHORISMS.$REQUEST['selected']);
        elseif (!file_exists(APHORISMS.SYSTEM::get('locale').'.txt')) $output['aph'] = file_get_contents(APHORISMS.SYSTEM::get('locale').'.txt');
        else                                                          $output['aph'] = file_get_contents(APHORISMS.'en.txt');

        $TPL = new TEMPLATE(dirname(__FILE__).DS.'aphorisms.tpl');
        echo $TPL->parse($output);
    }
} else {
    $output['title']  = __('Aphorisms');
    $output['select'] = $aphorisms;

    $TPL = new TEMPLATE(dirname(__FILE__).DS.'select.tpl');
    echo $TPL->parse($output);
}
