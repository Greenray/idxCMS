<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module APHORISMS

if (!defined('idxCMS')) die();
#
# Get file with aphorisms according to user's locale
#
$aphorism = file(APHORISMS.SYSTEM::get('locale').'.txt');
#
# Show random string after user click
#
if (!empty(FILTER::get('REQUEST', 'flip'))) {
    #
    # Processing of command "flip"
    #
    echo $aphorism[array_rand($aphorism, 1)].'$';

} else {
    $TPL = new TEMPLATE(__DIR__.DS.'aphorisms.tpl');
    $TPL->set('text', $aphorism[array_rand($aphorism, 1)]);
    #
    # Show aphorisms box with a random string after module init
    #
    SYSTEM::defineWindow('Aphorisms', $TPL->parse());
}
