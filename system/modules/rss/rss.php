<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# RSS FEEDS

if (!defined('idxCMS')) die();

$feeds = SYSTEM::get('feeds');
$feed  = FILTER::get('REQUEST', 'feed');

if (!empty($feed)) {
    if (CONFIG::getValue('enabled', 'rss')) {
        if (!empty($feeds[$feed])) {
            header("Content-type: text/xml; charset=UTF-8");
            $RSS = new RSS_FEED(
                CONFIG::getValue('main', 'title').' - '.$feeds[$feed][0],
                $feeds[$feed][1]
            );
            $RSS->getFeed($feed);
            echo $RSS->showFeed();
        }
    }
}
?>