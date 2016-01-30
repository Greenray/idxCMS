<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module SITEMAP

if (!defined('idxCMS')) die();

$TPL = new TEMPLATE(__DIR__.DS.'sitemap.tpl');
$TPL->set('points', CMS::call('SYSTEM')->getMainMenu());

SYSTEM::defineWindow('Sitemap', $TPL->parse());
