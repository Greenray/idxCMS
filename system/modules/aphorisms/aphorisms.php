<?php
# idxCMS Flat Files Content Management Sysytem
# Module Aphorizms
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

# Get file with aphorisms according to user's locale
$aph = file(APHORISMS.SYSTEM::get('locale').'.txt');

# Show random string
if (!empty(FILTER::get('REQUEST', 'flip'))) {

    # Processing of command "flip"
    echo $aph[array_rand($aph, 1)].'$';

} else {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'aphorisms.tpl');
    ShowWindow(__('Aphorisms'), $TPL->parse(['text' => $aph[array_rand($aph, 1)]]));
}
