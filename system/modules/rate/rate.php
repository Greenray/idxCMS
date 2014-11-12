<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE RATE

if (!defined('idxCMS')) die();

$act  = FILTER::get('REQUEST', 'act');
$id   = FILTER::get('REQUEST', 'id');
$user = FILTER::get('REQUEST', 'user');

if (!empty($act) && !empty($id) && !empty($user)) {
    $time = microtime(TRUE);
    $rated = md5(USER::getUser('username').$id);
    if (!file_exists(TEMP.$rated)) {
        file_put_contents(TEMP.$rated, $time, LOCK_EX);
        $result = RateComment($user, $act, $id);
    } else {
        $old_time = file_get_contents(TEMP.$rated);
        if (($time - $old_time) > CONFIG::getValue('user', 'timeout')) {
            file_put_contents(TEMP.$rated, $time, LOCK_EX);
            $result = RateComment($user, $act, $id);
        } else {
            $result = FILTER::get('REQUEST', 'rate');
        }
    }
    if (!empty($result)) echo $result.'$';
}

$value = FILTER::get('REQUEST', 'val');

if (!empty($value) && !empty($id)) {
    $user = USER::getUser('username');
    if ($user !== 'guest') {
        $item = '';
        $rate = GetRate($id, $item);
        $rate[$user] = intval($value);
        file_put_contents($item, serialize($rate), LOCK_EX);
        echo ShowRate($id).'$';
    }
}
?>