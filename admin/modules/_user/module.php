<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Users managment.

if (!defined('idxADMIN')) die();

$MODULES[$module][0] = __('Users');
$MODULES[$module][1]['config']   = __('Configuration');
$MODULES[$module][1]['profile']  = __('User');
$MODULES[$module][1]['bans']     = __('Bans');
$MODULES[$module][1]['feedback'] = __('Feedback');
$MODULES[$module][1]['message']  = __('Administrative message for users');
