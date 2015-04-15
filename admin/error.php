<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$output = [];
$output['locale']   = SYSTEM::get('locale');
$output['message1'] = $message[0];
$output['message2'] = $message[1];

$TPL = new TEMPLATE(TEMPLATES.'error.tpl');
echo $TPL->parse($output);
