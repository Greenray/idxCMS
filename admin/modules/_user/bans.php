<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Bans managment.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['ban'])) {
    $bans = [];
    foreach ($REQUEST['ban'] as $key => $ban) {
        if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).) {2}(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]|\*).)([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]|\*)$/', $ban)) {
            $bans[] = $ban;
        }
    }
    if (!empty($bans)) {
        natsort($bans);
        file_put_contents(CONTENT.'bans', implode(LF, $bans).LF, LOCK_EX);
    } else {
        file_put_contents(CONTENT.'bans', '', LOCK_EX);
    }
}

if (!$bans = file(CONTENT.'bans', FILE_IGNORE_NEW_LINES)) {
    $bans = [];
}

$TEMPLATE = new TEMPLATE(__DIR__.DS.'bans.tpl');
$TEMPLATE->set('bans', $bans);
echo $TEMPLATE->parse();
