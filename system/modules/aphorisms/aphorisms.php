<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module APHORISMS

if (!defined('idxCMS')) die();
#
# Get file with aphorisms according to user's locale
#
$aphorisms = file(APHORISMS.SYSTEM::get('locale').'.txt');
#
# Show random string after user click
#
if (!empty(FILTER::get('REQUEST', 'flip'))) {
    #
    # Processing of command "flip"
    #
    echo $aphorisms[array_rand($aphorisms, 1)].'$';

} else {
    $TPL = new TEMPLATE(__DIR__.DS.'aphorisms.tpl');
    $TPL->set('text', $aphorisms[array_rand($aphorisms, 1)]);
    SYSTEM::defineWindow('Aphorisms', $TPL->parse());
}
