<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::loggedIn()) die();

$output = [];
$output['locale'] = SYSTEM::get('locale');

$navigation = GetUnserialized(CONTENT.'menu');
foreach ($navigation as $k => $item) {
    $output['menu'][$k]['link'] = $item['link'];
    $output['menu'][$k]['name'] = $item['name'];
    $output['menu'][$k]['icon'] = !empty($item['icon']) ? $item['icon'] : '';
}
foreach($MODULES as $category => $data) {
    if (!empty($data[1])) {
        if (is_array($data[1])) {
            $output['modules'][$category]['name'] = $data[0];
            foreach($data[1] as $module => $title) {
                $output['modules'][$category]['module'][$module]['category'] = $category;
                $output['modules'][$category]['module'][$module]['module']   = $module;
                $output['modules'][$category]['module'][$module]['title']    = $title;
            }
        } elseif ($data[0] === $data[1]) {
            $output['modules'][$category]['nomodule'] = TRUE;
            $output['modules'][$category]['name']     = $data[0];
            $output['modules'][$category]['category'] = $category;
        }
    }
}
$TPL = new TEMPLATE(ADMINTEMPLATES.'navigation.tpl');
echo $TPL->parse($output);
