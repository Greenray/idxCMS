<?php
# idxCMS Flat Files Content Management Sysytem

/** Flash and text tagcloud.
 * Module registration and internal functions.
 * @file      system/modules/tagcloud/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Tagcloud
 */

if (!defined('idxCMS')) die();

/** Transformation of an array: key <=> value.
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

/** Transformation of a two-dimensional array into one-dimensional with restoration of reference values of keys.
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

/** Preparing tags for tagcloud.
 * @return array Array of tags for the tagcloud
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

/** Callback function for tags sorting
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
