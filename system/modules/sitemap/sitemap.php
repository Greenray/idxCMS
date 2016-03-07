<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module SITEMAP

if (!defined('idxCMS')) die();

$TEMPLATE = new TEMPLATE(__DIR__.DS.'sitemap.tpl');
$TEMPLATE->set('points', CMS::call('SYSTEM')->getMainMenu());
SYSTEM::defineWindow('Sitemap', $TEMPLATE->parse());
