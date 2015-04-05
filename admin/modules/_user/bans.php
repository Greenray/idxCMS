<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['ban'])) {
    $bans = [];
    foreach ($REQUEST['ban'] as $key => $ban) {
        if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){2}(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]|\*).)([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]|\*)$/', $ban)) {
            $bans[] = $ban;
        }
    }
    if (!empty($bans)) {
        natsort($bans);
        file_put_contents(CONTENT.'bans', implode(LF, $bans).LF);
    } else {
        file_put_contents(CONTENT.'bans', '');
    }
}

if (!$bans = file(CONTENT.'bans', FILE_IGNORE_NEW_LINES)) {
    $bans = [];
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'bans.tpl');
echo $TPL->parse(array('ban' => $bans));
