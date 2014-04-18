<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - STATISTIC CONFIGURATION

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    $config = array();
    $config['user-ua']   = empty($REQUEST['user-ua'])   ? '' : '1';
    $config['spider-ip'] = empty($REQUEST['spider-ip']) ? '' : '1';
    $config['spider-ua'] = empty($REQUEST['spider-ua']) ? '' : '1';
    CMS::call('CONFIG')->setSection('statistic', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save configuration');
    }
}
$config = CONFIG::getSection('statistic');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
?>