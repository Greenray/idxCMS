<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE POSTS - NEWS

if (!defined('idxCMS')) die();

$categories = CMS::call('POSTS')->getCategories('news');
$output = CMS::call('POSTS')->getCategoryLastItems('d F Y H:i:s');

if (!empty($output)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'last.tpl');
    ShowWindow(__('Last news'), $TPL->parse($output));
}
?>