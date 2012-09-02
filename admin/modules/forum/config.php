<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FORUM - CONFIGURATION

if (!defined('idxADMIN')) die();

$config = CONFIG::getSection('forum');
if (isset($init)) {
    if (empty($config)) {
        $config['topics-per-page']  = 10;
        $config['replies-per-page'] = 10;
        $config['reply-length']     = 4000;
        CMS::call('CONFIG')->setSection('forum', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config = array();
        $config['topics-per-page']  = empty($REQUEST['topics-per-page'])  ? 10   : (int) $REQUEST['topics-per-page'];
        $config['replies-per-page'] = empty($REQUEST['replies-per-page']) ? 10   : (int) $REQUEST['replies-per-page'];
        $config['reply-length']     = empty($REQUEST['reply-length'])     ? 4000 : (int) $REQUEST['reply-length'];
        CMS::call('CONFIG')->setSection('forum', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save configuration');
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>