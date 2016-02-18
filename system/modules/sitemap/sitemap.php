<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module SITEMAP

if (!defined('idxCMS')) die();

$TEMPLATE = new TEMPLATE(__DIR__.DS.'sitemap.tpl');
$TEMPLATE->set('points', CMS::call('SYSTEM')->getMainMenu());
SYSTEM::defineWindow('Sitemap', $TEMPLATE->parse());
