<?php
# idxCMS Flat Files Content Management Sysytem
# Module RSS
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
