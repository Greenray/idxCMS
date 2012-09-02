<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - APHORISMS

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    if (!empty($REQUEST['aph']) && !empty($REQUEST['file'])) {
        if (!file_put_contents(APHORISMS.$REQUEST['file'], $REQUEST['aph'])) {
            ShowMessage(__('Cannot save file').' '.$REQUEST['file']);
        }
    }
}

$aphorisms = array_values(GetFilesList(APHORISMS));
if (!empty($REQUEST['aph'])) {
    $output = array();
    if (in_array($REQUEST['aph'], $aphorisms)) {
        $output['file'] = $REQUEST['aph'];
        if (file_exists(APHORISMS.$REQUEST['aph'])) {
            $output['aph'] = file_get_contents(APHORISMS.$REQUEST['aph']);
        } elseif (!file_exists(APHORISMS.SYSTEM::get('locale').'.txt')) {
            $output['aph'] = file_get_contents(APHORISMS.SYSTEM::get('locale').'.txt');
        } else {
            $output['aph'] = file_get_contents(APHORISMS.'en.txt');
        }
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'aphorisms.tpl');
        echo $TPL->parse($output);
    }
} else {
    $output['aph'] = $aphorisms;
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'select.tpl');
    echo $TPL->parse($output);
}
?>