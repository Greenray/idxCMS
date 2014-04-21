<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE APHORISMS

if (!defined('idxCMS')) die();

$aph = file(APHORISMS.SYSTEM::get('locale').'.txt');

if (FILTER::get('REQUEST', 'flip')) {
    echo $aph[array_rand($aph, 1)].'$';    # Show random string
} else {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'aphorism.tpl');
    ShowWindow(__('Aphorisms'), $TPL->parse(array('text' => $aph[array_rand($aph, 1)])));
}
?>