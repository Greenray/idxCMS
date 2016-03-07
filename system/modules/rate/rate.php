<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module RATE

if (!defined('idxCMS')) die();

if (!empty($REQUEST['act']) && !empty($REQUEST['id']) && !empty($REQUEST['user'])) {
    $time  = microtime(TRUE);
    $rated = md5(USER::getUser('user').$REQUEST['id']);

    if (!file_exists(TEMP.$rated)) {
        file_put_contents(TEMP.$rated, $time, LOCK_EX);
        $result = RateComment($REQUEST['user'], $REQUEST['act'], $REQUEST['id']);

    } else {
        $old_time = file_get_contents(TEMP.$rated);

        if (($time - $old_time) > CONFIG::getValue('user', 'timeout')) {
            file_put_contents(TEMP.$rated, $time, LOCK_EX);
            $result = RateComment($REQUEST['user'], $REQUEST['act'], $REQUEST['id']);

        } else {
            $result['rate']  = $REQUEST['rate'];
            $result['stars'] = '';
        }
    }

    if ($result['rate'] > 0)   $style = "color:green;";
    if ($result['rate'] < 0)   $style = "color:red;";
    if ($result['rate'] === 0) $style = "color:black;";

    echo $result['rate'].'$'.$style.'$'.$result['stars'];
}

if (!empty($REQUEST['val']) && !empty($REQUEST['id'])) {
    $user = USER::getUser('user');
    if ($user !== 'guest') {
        $item = '';
        $rate = GetRate($REQUEST['id'], $item);
        $rate[$user] = $REQUEST['val'];
        file_put_contents($item, json_encode($rate, JSON_UNESCAPED_UNICODE), LOCK_EX);
        echo ShowRate($REQUEST['id']).'$';
    }
}
