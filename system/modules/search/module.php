<?php
/** Search the website.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/searc/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Search
 * @overview  Search the website.
 *            Search by key words of the tag cloud or arbitrary text.
 */

if (!defined('idxCMS')) die();

/** Creates the array of search results.
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
        if (stripos($text, $word, 0) !== FALSE) {
            if (!array_key_exists($link, $result)) {
                $result[$link] = $word.'|'.$title.'|'.$text;
            }
        }
    }
}

SYSTEM::registerModule('search', 'Search', 'box', 'system');
