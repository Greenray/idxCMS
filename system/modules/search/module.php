<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE SEARCH - INITIALIZATION

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
//        if (mb_stripos($text, $word, 0) !== FALSE) {    # This is for php 5.2 >
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
//    $target  = mb_stristr($text, $word);  # This is for php 5.2 >
    $target = stristr($text, $word);
    $real   = mb_substr($target, 0, mb_strlen($word));
    $start  = 0;
    $start_ = '';
    $end_   = '';
//    $temp    = mb_stripos($text, $word);  # This is for php 5.2 >
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
?>