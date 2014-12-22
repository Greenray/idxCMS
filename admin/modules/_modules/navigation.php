<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - NAVIGATION

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

# Save site menu
if (!empty($REQUEST['save'])) {
    $result = [];
    foreach ($REQUEST['links'] as $i => $link) {
        if (!empty($link)) {
            $ins[0] = $link;
            $ins[1] = $REQUEST['names'][$i];
            $ins[2] = $REQUEST['descs'][$i];
            $ins[3] = $REQUEST['icons'][$i];
            $result[] = $ins;
        }
    }
    CMS::call('CONFIG')->setSection('navigation', $result);
    if (!CMS::call('CONFIG')->save()) {
         ShowMessage('Cannot save file');
    }
}

$icons = GetFilesList(ICONS);
$links = CONFIG::getSection('navigation');
$output = [];

foreach ($links as $key => $values) {
    $i = 0;
    $output['links'][$key]['link'] = $values[0];
    $output['links'][$key]['name'] = $values[1];
    $output['links'][$key]['desc'] = $values[2];
    $output['links'][$key]['icon'] = $values[3];
    foreach ($icons as $icon) {
        $output['links'][$key]['icons'][$i]['id'] = $icon;
        if ($values[3] === $icon) {
            $output['links'][$key]['icons'][$i]['selected'] = TRUE;
        }
        ++$i;
    }
}

$output['links'][$i] = array(
    'link' => '',
    'name' => '',
    'desc' => '',
    'icon' => ''
);

foreach ($icons as $icon) {
    $output['links'][$i]['icons'][]['id'] = $icon;
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'navigation.tpl');
echo $TPL->parse($output);
?>