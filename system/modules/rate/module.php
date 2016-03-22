<?php
/**
 * Rates.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.1 International
 * @file      system/modules/rate/module.php
 * @package   Rate
 * @overview  Rates for articles and comments.
 */

if (!defined('idxCMS')) die();

/**
 * Rate publication.
 *
 * @param  string  $user User name
 * @param  string  $act  Action: up|down rate
 * @param  string  $id   Post ID
 * @return integer|boolean Rate value or FALSE
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
    $comments = json_decode(file_get_contents($file), TRUE);
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
            file_put_contents($file, json_encode($comments, JSON_UNESCAPED_UNICODE), LOCK_EX);
            CMS::call('USER')->saveUserData($user['user'], $user);
        }
        $result['rate'] = $comments[$id]['rate'];
        $result['stars'] = $user['stars'];
        return $result;
    }
    return FALSE;
}

/**
 * Gets rate for publication.
 *
 * @param  string $for  Publication ID
 * @param  string $item Item
 * @return array        Rate data
 */
function GetRate($for, &$item) {
    $item = explode('.', $for);
    if (!empty($item[3]))
         $item = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.$item[3].DS.'rate';
    else $item = CONTENT.$item[0].DS.$item[1].DS.$item[2].DS.'rate';
    if (file_exists($item))
         return json_decode(file_get_contents($item), TRUE);
    else return [];
}

/**
 * Shows rate for publication.
 *
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
            $value = $sum / $voices;
        }
    }
    $user = USER::getUser('user');

    if (($user !== 'guest') && !array_key_exists($user, $rate)) {
        $event = 'onmousedown="star.update(this, \''.$for.'\')" onmousemove="star.mouse(event, this)" title="Rate!"';
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'rate.tpl');
    $TEMPLATE->set('value', $value);                      # Rate value
    $TEMPLATE->set('voted', $voices);                     # Number of voices
    $TEMPLATE->set('item' , $for);                        # Item
    $TEMPLATE->set('width', $value * 84 / 100);           # Width of rate value field
    $TEMPLATE->set('event', $event);                      # Allow rating?
    return $TEMPLATE->parse();
}

SYSTEM::registerModule('rate', 'Rate', 'plugin');
