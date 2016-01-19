<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Statistcs, logs and search keywords managment.


if (!defined('idxADMIN')) die();

$MODULES[$module][0] = __('Site statistics');
$MODULES[$module][1]['config']      = __('Configuration');
$MODULES[$module][1]['statistics']  = __('Site statistics');
$MODULES[$module][1]['logs']        = __('Control logs');
$MODULES[$module][1]['searchwords'] = __('Search keywords');
