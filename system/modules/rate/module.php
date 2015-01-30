<?php
# idxCMS Flat Files Content Management Sysytem

/** Rate system for comments and replays.
 * Module registration and internal functions.
 * @file      system/modules/posts/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Rate
 */

if (!defined('idxCMS')) die();

/**
 * Rate publication.
 * @param  string  $user           User name
 * @param  string  $act            Action: up|down rate
 * @param  string  $id             Post ID
 * @return boolean integer|boolean Rate value or FALSE
 */
function RateComment($user, $act, $id) {
    $item = explode('.', $id);
    if (empty($item[4])) {
        $file = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.'index';
        $id = $item[3];
    } else {
        $file = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.$item[3].DS.'index';
        $id = $item[4];
    }
    $comments = GetUnserialized($file);
    if (!empty($comments[$id])) {
        $user = USER::getUserData($user);
        if (!empty($user)) {
            if ($act === 'up') {
                ++$comments[$id]['rate'];
                ++$user['stars'];
            } else {
                if ($act === 'dn') {
                    --$comments[$id]['rate'];
                    --$user['stars'];
                }
            }
            file_put_contents($file, serialize($comments), LOCK_EX);
            CMS::call('USER')->saveUserData($user['username'], $user);
        }
        return $comments[$id]['rate'];
    }
    return FALSE;
}

/**
 * Get rate for publication.
 * @param  string $for  Publication ID
 * @param  string $item Item
 * @return array        Rate data
 */
function GetRate($for, &$item) {
    $item = explode('.', $for);
    if (!empty($item[3])) {
        $item = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.$item[3].DS.'rate';
    } else {
        $item = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.'rate';
    }
    return GetUnserialized($item);
}

/**
 * Show rate for publication.
 * @param  string $for Publication ID
 * @return string      Parsed rate results
 */
function ShowRate($for) {
    $value  = 0;
    $voices = 0;
    $event  = '';
    $item   = '';
    $rate   = GetRate($for, $item);
    if (!empty($rate)) {
        $voices = sizeof($rate);
        $sum = 0;
        foreach ($rate as $key => $op) {
            $sum = $sum + $op;
        }
        if ($voices !== 0) {
            $value = intval($sum / $voices);
        }
    }
    $user = USER::getUser('username');
    if (($user !== 'guest') && !array_key_exists($user, $rate)) {
        $event = 'onmousedown="star.update(this, \''.$for.'\')" onmousemove="star.mouse(event, this)" title="Rate!"';
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'rate.tpl');
    return $TPL->parse([
        'value' => $value,                      # Rate value
        'voted' => $voices,                     # Number of voices
        'item'  => $for,                        # Item
        'width' => intval($value * 84 / 100),   # Width of rate value field
        'event' => $event                       # Allow rating?
    ]);
}

SYSTEM::registerModule('rate', 'Rate', 'plugin');
