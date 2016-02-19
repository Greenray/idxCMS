<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Modules management.

if (!defined('idxADMIN') || !USER::$root) die();

CMS::call('SYSTEM')->initModules(TRUE);
$registered_modules = SYSTEM::get('modules');
$enabled = CONFIG::getSection('enabled');
$unset   = $enabled;

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
    try {
        CMS::call('CONFIG')->setSection('enabled', $enabled);
        if (!empty($unset)) {
            foreach($unset as $mod => $active) {
                CMS::call('CONFIG')->unsetSection($mod);
            }
        }
        CMS::call('CONFIG')->save();
        Sitemap();

    } catch (Exception $error) {
        ShowError($error->getMessage());
    }
}

$output  = [];
$modules = [];
$i = 0;

foreach ($registered_modules as $mod => $values) {
    if (strpos($mod, '.')) {
        $id = explode('.', $mod, 2);
        if (in_array($id[0], $modules)) {
            $output[$id[0]]['ext'][$mod]['module'] = $mod;
            $output[$id[0]]['ext'][$mod]['title']  = $values['title'];
            if (!empty($values['system'])) {
                $output[$id[0]]['ext'][$mod]['system'] = TRUE;
                $output[$id[0]]['ext'][$mod]['class']  = 'dark';
            } else {
                $output[$id[0]]['ext'][$mod]['class']  = 'light';
            }
            if (!empty($enabled[$mod])) {
                $output[$id[0]]['ext'][$mod]['enabled'] = TRUE;
            }
        } else {
            ++$i;
            $modules[$i] = $mod;
            $output[$id[0]]['module'] = $mod;
            $output[$id[0]]['title']  = $values['title'];
            if (!empty($values['system'])) $output[$id[0]]['system']  = TRUE;
            if (!empty($enabled[$mod]))    $output[$id[0]]['enabled'] = TRUE;
            $output[$id[0]]['ext'] = [];
        }
    } else {
        if (!in_array($mod, $modules)) {
            ++$i;
            $modules[$i] = $mod;
            $output[$mod]['module'] = $mod;
            $output[$mod]['title']  = $values['title'];
            if (!empty($values['system'])) $output[$mod]['system']  = TRUE;
            if (!empty($enabled[$mod]))    $output[$mod]['enabled'] = TRUE;
            $output[$mod]['ext'] = [];
        }
    }
}

$TEMPLATE = new TEMPLATE(__DIR__.DS.'modules.tpl');
$TEMPLATE->set('modules', $output);
echo $TEMPLATE->parse();
