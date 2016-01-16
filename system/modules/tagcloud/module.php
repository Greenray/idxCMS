<?php
/**
 * Flash and text tagcloud.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/modules/tagcloud/module.php
 * @package   Tagcloud
 * @overview  Plugin wp-cumulus:
 *            Roy Tanck - (с) Flash based Tag Cloud for WordPress (www.roytanck.com)
 *            Released under GNU General Public License (GPL) Version 3, 29 June 2007 (http://opensource.org/licenses/gpl-3.0.php).
 *            SWFObject v1.4: Flash Player detection and embed - http://blog.deconcept.com/swfobject/
 *            SWFObject is (c) 2006 Geoff Stearns and is released under the MIT License: http://www.opensource.org/licenses/mit-license.php
 *            SWFObject is the SWF embed script formarly known as FlashObject. The name was changed for legal reasons.
 */

if (!defined('idxCMS')) die();

/**
 * Transforms an array: key <=> value.
 *
 * @param  array $array Array to invert
 * @return array        Inverted array
 */
function ArrayInvert($array) {
    $result = [];
    foreach (array_keys($array) as $key) {
        if (!array_key_exists($array[$key], $result)) {
            $result[$array[$key]] = [];
        }
        array_push($result[$array[$key]], $key);
    }
    return $result;
}

/**
 * Transforms two-dimensional array into one-dimensional with restoration of reference values of keys.
 *
 * @param  array $array Array to transform
 * @return array        The result of transformation
 */
function ArrayNormalize($array) {
    $result = [];
    foreach ($array as $key => $items) {
        foreach ($items as $arr => $value) {
            $result[$value] = $key;
        }
    }
    return $result;
}

/**
 * Prepares tags for tagcloud.
 *
 * @return array Tags for the tagcloud
 */
function PrepareTags() {
    $tags = GetUnserialized(CONTENT.'tags');
    if (!empty($tags)) {
        $tags = ArrayInvert($tags);
        krsort($tags);
        $tags = ArrayNormalize($tags);
    }
    return $tags;
}

/**
 * Callback function for tags sorting.
 *
 * @param  string  $a First tag for comparing
 * @param  string  $b Second tag for comparing
 * @return integer    The result of operation
 */
function scmp($a, $b) {
    return mt_rand(-1, 1);
}


switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Tagcloud'] = 'Облако тегов';
        break;

    case 'ua':
        $LANG['def']['Tagcloud'] = 'Хмара тегів';
        break;

    case 'by':
        $LANG['def']['Tagcloud'] = 'Воблака тэгаў';
        break;
}

SYSTEM::registerModule('tagcloud', 'Tagcloud', 'box');
