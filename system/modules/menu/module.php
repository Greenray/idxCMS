<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE MENU - INITIALIZATION

if (!defined('idxCMS')) die();

SYSTEM::registerModule('menu', 'Menu', 'box', 'system');
SYSTEM::registerModule('menu.simple', 'Simple menu', 'box', 'system');
SYSTEM::registerModule('menu.navigation', 'Navigation', 'box', 'system');
?>