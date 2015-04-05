<?php
/** Module SEARCH - initialization.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/search/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Search
 */

if (!defined('idxCMS')) die();

/** Formats result of seaching for output.
 *
 * @param  string $text   Text data where the search will be carried out
 * @param  string $title  Title of the post where the search query was found
 * @param  string $word   Search query
 * @param  string $link   Source link
 * @param  string $result The result of formatting
 * @return &$result
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
        if (stripos($text, $word, 0) !== FALSE) {
            if (!array_key_exists($link, $result)) {
                $result[$link] = $word.'|'.$title.'|'.$text;
            }
        }
    }
}

/** Format output of search results.
 *
 * @param  string  $text   Text data to format for output
 * @param  string  $word   Search query
 * @param  integer $config Max length for output
 * @return string          Formatted text for output
 */
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
