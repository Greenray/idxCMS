<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE TAGCLOUD - INITIALIZATION

if (!defined('idxCMS')) die();

# Transformation of a array: key <=> value
function ArrayInvert($array) {
    $result = array();
    foreach (array_keys($array) as $key) {
        if (!array_key_exists($array[$key], $result)) {
            $result[$array[$key]] = array();
        }
        array_push($result[$array[$key]], $key);
    }
    return $result;
}

# Transformation of a two-dimensional array into one-dimensional
# with restoration of reference values of keys
function ArrayNormalize($array) {
    $result = array();
    foreach ($array as $key => $items) {
        foreach ($items as $arr => $value) {
            $result[$value] = $key;
        }
    }
    return $result;
}

function PrepareTags() {
    $tags = GetUnserialized(CONTENT.'tags');
    if (!empty($tags)) {
        $tags = ArrayInvert($tags);
        krsort($tags);
        $tags = ArrayNormalize($tags);
    }
    return $tags;
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
?>