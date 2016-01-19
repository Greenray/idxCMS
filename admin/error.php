<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Error message.

if (!defined('idxCMS')) die();

$output = [];
$output['locale']   = SYSTEM::get('locale');
$output['message1'] = $message[0];
$output['message2'] = $message[1];

$TPL = new TEMPLATE(TEMPLATES.'error.tpl');
$TPL->set($output);
echo $TPL->parse();
