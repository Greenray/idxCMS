<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE NAVIGATION

if (!defined('idxCMS')) die();

$TPL = new TEMPLATE(dirname(__FILE__).DS.'navigation.tpl');
ShowWindow(__('Navigation'), $TPL->parse(array('points' => CMS::call('SYSTEM')->getNavigation())));
?>