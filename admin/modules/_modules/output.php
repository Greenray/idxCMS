<?php
/**
 * @package   ADMINISTRATION
 * @ingroup   MODULES GENERAL CONFIG
 * @file      admin/modules/_modules/output.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 * @see       https://github.com/Greenray/idxCMS/admin/modules/_modules/output.php
 */

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    # Save configuration
    $config = array();
    $page   = '';
    if (!empty($REQUEST['active'])) {
        foreach ($REQUEST['active'] as $element) {
            if (substr($element, 0, 1) === DS) {
                $page = substr($element, 1);
                $config[$page] = array();
            } else {
                $config[$page][] = trim(substr($element, 1));
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
        ShowMessage('Cannot save file');
    }
}

# INTERFACE
if (!empty($REQUEST['selected'])) {
    $panel = CONFIG::getSection('output.'.$REQUEST['selected']); /**< Modules layout for specified skin */
    include(SKINS.$REQUEST['selected'].DS.'skin.php');  # Layout definition
    $active = array(); /**< Active modules */
    $unused = array(); /**< Unused modules */

    foreach ($panel as $point => $list) {
        if (!empty($SKIN[$point])) {
             $active[DS.$point] = $SKIN[$point];
        } else {
            $active[DS.$point] = __('Page').': '.SYSTEM::$modules[$point]['title'];
        }
        foreach ($list as $i => $box) {
            $key = '>'.$box;
            while (array_key_exists($key, $active)) {
                $box = ' '.$box;
                $key = '>'.$box;
            }
            $active[$key] = __('Box').': '.SYSTEM::$modules[$list[$i]]['title'];
        }
    }

    foreach ($SKIN as $point => $desc) {
        if (!isset($active[DS.$point])) {
            $unused[DS.$point] = $desc;
        }
    }

    foreach (SYSTEM::$modules as $id => $module) {
        if (!isset($active[DS.$id]) && !isset($active['>'.$id])) {
            if ($module['type'] === 'main') {
                $unused[DS.$id] = __('Page').': '.$module['title'];
            } elseif ($module['type'] === 'box') {
                $unused['>'.$id] = __('Box').': '.$module['title'];
            }
        }
    }

    $output = array();
    $output['skin']   = $REQUEST['selected'];
    $output['active'] = $active;
    $output['unused'] = $unused;

    $TPL = new TEMPLATE(dirname(__FILE__).DS.'output.tpl');
    echo $TPL->parse($output);

} else {
    $output['title']  = __('Output management');
    $output['select'] = explode(',', CONFIG::getValue('main', 'skins'));
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'select.tpl');
    echo $TPL->parse($output);
}
