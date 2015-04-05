<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$TPL = new TEMPLATE(dirname(__FILE__).DS.'navigation.tpl');
ShowWindow(__('Navigation'), $TPL->parse(array('points' => CMS::call('SYSTEM')->getNavigation())));
