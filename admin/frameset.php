<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::loggedIn()) die();

$output = [];
$output['locale'] = SYSTEM::get('locale');

$TPL = new TEMPLATE(TEMPLATES.'frameset.tpl');
echo $TPL->parse($output);
