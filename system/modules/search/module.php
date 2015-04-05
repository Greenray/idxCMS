<?php
# idxCMS Flat Files Content Management Sysytem
# Module Search
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

function SearchResult($text, $title, $word, $link, &$result) {
    $word = trim($word);
    if (!empty($word)) {
        $text = str_replace('<br />', ' ', $text);
        $text = str_replace('<br/>', ' ', $text);
        $text = preg_replace('/\[code\](.*?)\[\/code\]/is', '', $text);
        $text = preg_replace('/\[php\](.*?)\[\/php\]/is',   '', $text);
        $text = preg_replace('/\[html\](.*?)\[\/html\]/is', '', $text);
        $text = preg_replace('/\[quote(.*?)\[\/quote\]/is', '', $text);
        $text = preg_replace('/\[(.*?)\]/is', '', $text);
        $text = strip_tags($text);
        if (stripos($text, $word, 0) !== FALSE) {
            if (!array_key_exists($link, $result)) {
                $result[$link] = $word.'|'.$title.'|'.$text;
            }
        }
    }
}

# Format output of search results.
function FormatFound($text, $word, $config) {
    $strlen = mb_strlen($text);
    $target = stristr($text, $word);
    $real   = mb_substr($target, 0, mb_strlen($word));
    $start  = 0;
    $start_ = '';
    $end_   = '';
    $temp   = stripos($text, $word);
    if ($temp > ($config / 2)) {
        $start  = $temp - ($config / 2);
        $start_ = '...';
    }
    if (($strlen - ($config / 2)) > $temp) {
        $strlen = $config - 1;
        $end_   = '...';
    }
    return $start_.str_replace($real, '<u><strong><em>'.$real.'</em></strong></u>', mb_substr($text, $start, $strlen)).$end_;
}

SYSTEM::registerModule('search', 'Search', 'box', 'system');
