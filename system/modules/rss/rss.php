<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module RSS

if (!defined('idxCMS')) die();

$feeds = SYSTEM::get('feeds');
$feed  = $REQUEST['feed'];

if (!empty($feed)) {
    if (CONFIG::getValue('enabled', 'rss')) {
        if (!empty($feeds[$feed])) {
            header("Content-type: text/xml; charset=UTF-8");
            $RSS = new RSS_FEEDS(
                CONFIG::getValue('main', 'title').' - '.$feeds[$feed][0],
                $feeds[$feed][1]
            );
            $RSS->getFeed($feed);
            echo $RSS->showFeed();
        }
    }
}
