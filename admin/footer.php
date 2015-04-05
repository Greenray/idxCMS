<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::loggedIn()) die();

$output = [];
$output['locale'] = SYSTEM::get('locale');

$TPL = new TEMPLATE(ADMINTEMPLATES.'footer.tpl');
echo $TPL->parse($output);
