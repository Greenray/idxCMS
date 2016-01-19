<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module SITEMAP

if (!defined('idxCMS')) die();

$TPL = new TEMPLATE(__DIR__.DS.'sitemap.tpl');
$TPL->set('points', CMS::call('SYSTEM')->getMainMenu());

SYSTEM::defineWindow('Sitemap', $TPL->parse());
