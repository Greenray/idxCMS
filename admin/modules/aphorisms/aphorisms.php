<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Aphorisms.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    if (!empty($REQUEST['aph']) && !empty($REQUEST['file'])) {
        if (!file_put_contents(APHORISMS.$REQUEST['file'], $REQUEST['aph'], LOCK_EX)) {
             ShowError('Cannot save file'.' '.$REQUEST['file']);
        }
    }
}

$aphorisms = array_values(GetFilesList(APHORISMS));

if (!empty($REQUEST['selected'])) {
    #
    # View and edit selected file
    #
    $output = [];
    if (in_array($REQUEST['selected'], $aphorisms)) {
        $output['file'] = $REQUEST['selected'];

        if     (file_exists(APHORISMS.$REQUEST['selected']))          $output['aph'] = file_get_contents(APHORISMS.$REQUEST['selected']);
        elseif (!file_exists(APHORISMS.SYSTEM::get('locale').'.txt')) $output['aph'] = file_get_contents(APHORISMS.SYSTEM::get('locale').'.txt');
        else                                                          $output['aph'] = file_get_contents(APHORISMS.'en.txt');

        $TEMPLATE = new TEMPLATE(__DIR__.DS.'aphorisms.tpl');
        $TEMPLATE->set($output);
        echo $TEMPLATE->parse();
    }
} else {
    #
    # Select file to view or edit
    #
    $output['title']  = __('Aphorisms');
    $output['select'] = $aphorisms;

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'select.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();
}
