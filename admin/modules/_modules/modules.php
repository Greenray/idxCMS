<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - MODULES

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

CMS::call('SYSTEM')->initModules(TRUE);
$registered_modules = SYSTEM::get('modules');
$enabled = CONFIG::getSection('enabled');
$unset = $enabled;

if (!empty($REQUEST['enable'])) {
    $enabled = [];
    foreach ($REQUEST['enable'] as $mod => $active) {
        $id = explode('.', $mod, 2);
        if (empty($id[1])) {
            $enabled[$id[0]] = '1';
            if (empty($registered_modules[$id[0]]['system'])) {
                if (file_exists(ADMIN.'modules'.DS.$id[0].DS.'config.php')) {
                    $init = TRUE;
                    include ADMIN.'modules'.DS.$id[0].DS.'config.php';
                    unset($init);
                }
            }
        }
        if (!empty($id[1]) && array_key_exists($id[0], $REQUEST['enable'])) {
            $enabled[$mod] = '1';
        }
        unset($unset[$mod]);
    }
    CMS::call('CONFIG')->setSection('enabled', $enabled);
    if (!CMS::call('CONFIG')->save()) {
         ShowMessage('Cannot save file');
    }
    if (!empty($unset)) {
        foreach($unset as $mod => $axtive) {
            CMS::call('CONFIG')->unsetSection($mod);
        }
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save file');
        }
    }
    Sitemap();
}

$output  = [];
$modules = [];
$i = 0;

foreach ($registered_modules as $mod => $values) {
    if (strpos($mod, '.')) {
        $id = explode('.', $mod, 2);
        if (in_array($id[0], $modules)) {
            $output['modules'][$id[0]]['ext'][$mod]['module'] = $mod;
            $output['modules'][$id[0]]['ext'][$mod]['title']  = $values['title'];
            if (!empty($values['system'])) {
                $output['modules'][$id[0]]['ext'][$mod]['system'] = TRUE;
                $output['modules'][$id[0]]['ext'][$mod]['class']  = 'even';
            } else {
                $output['modules'][$id[0]]['ext'][$mod]['class'] = 'odd';
            }
            if (!empty($enabled[$mod])) {
                $output['modules'][$id[0]]['ext'][$mod]['enabled'] = TRUE;
                $output['modules'][$id[0]]['ext'][$mod]['checked'] = 'checked="checked"';
            }
        } else {
            ++$i;
            $modules[$i] = $mod;
            $output['modules'][$id[0]]['module'] = $mod;
            $output['modules'][$id[0]]['title']  = $values['title'];
            if (!empty($values['system'])) {
                $output['modules'][$id[0]]['system'] = TRUE;
            }
            if (!empty($enabled[$mod])) {
                $output['modules'][$id[0]]['enabled'] = TRUE;
            }
            $output['modules'][$id[0]]['ext'] = [];
        }
    } else {
        if (!in_array($mod, $modules)) {
            ++$i;
            $modules[$i] = $mod;
            $output['modules'][$mod]['module'] = $mod;
            $output['modules'][$mod]['title']  = $values['title'];
            if (!empty($values['system'])) {
                $output['modules'][$mod]['system'] = TRUE;
            }
            if (!empty($enabled[$mod])) {
                $output['modules'][$mod]['enabled'] = TRUE;
            }
            $output['modules'][$mod]['ext'] = [];
        }
    }
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'modules.tpl');
echo $TPL->parse($output);
?>