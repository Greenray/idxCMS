<?php
/**
 * Search the website.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/modules/searc/module.php
 * @package   Search
 * @overview  Search the website.
 *            Search by key words of the tag cloud or arbitrary text.
 */

if (!defined('idxCMS')) die();

/**
 * Creates the array of search results.
 *
 * @param  string $text    The text to search
 * @param  string $title   The title of the item
 * @param  string $word    The search word
 * @param  string $link    The link to item
 * @param  array  &$result The reference to array of results
 * @return array           The array of results
 */
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
        if (stripos($text, $word, 0)) {
            if (!array_key_exists($link, $result)) {
                $result[$link] = $word.'|'.$title.'|'.$text;
            }
        }
    }
}

/**
 * Formats output of search results.
 *
 * @param  string  $text   Text to search in
 * @param  string  $word   Word to search for
 * @param  integer $config Length of search string
 * @return string          The result of search
 */
function FormatFound($text, $word, $config) {
    $strlen = mb_strlen($text);
    $target = mb_stristr($text, $word);
    $real   = mb_substr($target, 0, mb_strlen($word));
    $start  = 0;
    $start_ = '';
    $end_   = '';
    $temp   = mb_stripos($text, $word);
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
