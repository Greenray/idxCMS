<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - USERS, MESSAGE for USERS, FEEDBACK, BANS

if (!defined('idxADMIN')) die();

$MODULES[$module][0] = __('Users');
$MODULES[$module][1]['config']   = __('Configuration');
$MODULES[$module][1]['profile']  = __('User');
$MODULES[$module][1]['bans']     = __('Bans');
$MODULES[$module][1]['feedback'] = __('Feedback');
$MODULES[$module][1]['message']  = __('Administrative message for users');
?>