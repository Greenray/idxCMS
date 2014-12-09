<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE RATE

if (!defined('idxCMS')) die();

if (!empty($REQUEST['act']) && !empty($REQUEST['id']) && !empty($REQUEST['user'])) {
    $time  = microtime(TRUE);
    $rated = md5(USER::getUser('username').$REQUEST['id']);
    if (!file_exists(TEMP.$rated)) {
        file_put_contents(TEMP.$rated, $time, LOCK_EX);
        $result = RateComment($REQUEST['user'], $REQUEST['act'], $REQUEST['id']);
    } else {
        $old_time = file_get_contents(TEMP.$rated);
        if (($time - $old_time) > CONFIG::getValue('user', 'timeout')) {
            file_put_contents(TEMP.$rated, $time, LOCK_EX);
            $result = RateComment($REQUEST['user'], $REQUEST['act'], $REQUEST['id']);
        } else {
            $result = $REQUEST['rate'];
        }
    }
    if (!empty($result)) echo $result.'$';
}

if (!empty($REQUEST['val']) && !empty($REQUEST['id'])) {
    $user = USER::getUser('username');
    if ($user !== 'guest') {
        $item = '';
        $rate = GetRate($REQUEST['id'], $item);
        $rate[$user] = (int) ($REQUEST['val']);
        file_put_contents($item, serialize($rate), LOCK_EX);
        echo ShowRate($REQUEST['id']).'$';
    }
}
