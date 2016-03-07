<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Output management.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['save'])) {
    #
    # Save configuration
    #
    $config = [];
    $page   = '';
    if (!empty($REQUEST['active'])) {
        foreach ($REQUEST['active'] as $element) {
            if (substr($element, 0, 1) === DS) {
                $page = substr($element, 1);
                $config[$page] = [];
            } else {
                $config[$page][] = trim(substr($element, 4));
            }
        }
        foreach ($config as $page => $data) {
            if (empty($config[$page])) {
                unset($config[$page]);
            }
        }
    }
    CMS::call('CONFIG')->setSection('output.'.$REQUEST['skin'], $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowError('Cannot save file'.' config.ini');
    }
}
#
# INTERFACE
#
if (!empty($REQUEST['selected'])) {
    $panel = CONFIG::getSection('output.'.$REQUEST['selected']); # Modules layout for specified skin
    include SKINS.$REQUEST['selected'].DS.'skin.php';            # Layout definition
    $active = [];
    $unused = [];

    foreach ($panel as $point => $list) {
        if (!empty($SKIN[$point])) {
            $active[] = [
                'key'   => DS.$point,
                'value' => $SKIN[$point],
                'class' => "site-page"
            ];
        } else {
            $active[] = [
                'key'   => DS.$point,
                'value' => __('Page').': '.SYSTEM::$modules[$point]['title'],
                'class' => "site-page"
            ];
        }
        foreach ($list as $i => $box) {
            $key = '>'.$box;
            while (array_key_exists($key, $active)) {
                $box = ' '.$box;
                $key = '>'.$box;
            }
            $active[] = ['key' => $key, 'value' => __('Box').': '.SYSTEM::$modules[$list[$i]]['title']];
        }
    }

    foreach ($SKIN as $point => $desc) {
        $founded = FALSE;
        foreach ($active as $key => $array) {
            if ($array['key'] === DS.$point) {
                $founded = TRUE;
            }
        }
        if (!$founded) {
            $unused[] = [
                'key'   => DS.$point,
                'value' => $desc,
                'class' => "site-page"
            ];
        }
    }

    foreach (SYSTEM::$modules as $id => $module) {
        $founded = FALSE;
        foreach ($active as $key => $array) {
            if (($array['key'] === DS.$id) || ($array['key'] === '>'.$id)) {
                $founded = TRUE;
            }
        }
        if (!$founded) {
            if ($module['type'] === 'main') {
                $unused[] = [
                    'key'   => DS.$id,
                    'value' => __('Page').': '.$module['title'],
                    'class' => "site-page"
                ];
            } elseif ($module['type'] === 'box')  $unused[] = ['key' => '>'.$id, 'value' => __('Box').': '.$module['title']];
        }
    }

    $output = [];
    $output['skin']   = $REQUEST['selected'];
    $output['active'] = $active;
    $output['unused'] = $unused;

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'output.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();

} else {
    $output['title']  = __('Output management');
    $output['select'] = AdvScanDir(SKINS, '', 'dir', FALSE, ['images']);

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'select.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();
}
