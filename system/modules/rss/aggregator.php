<?php
# idxCMS Flat Files Content Management Sysytem
# Module RSS
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$rss_cfg = CONFIG::getSection('rss-aggregator');
$rss     = new RSS_AGGREGATOR();
$rss->cache_time  = $rss_cfg['cache-time'];
$rss->cache_dir   = CONTENT.'rss-cache';
$rss->items_limit = CONFIG::getValue('main', 'last');

if (function_exists('iconv')) {
    $rss->default_cp = 'UTF-8';
    $rss->cp = 'UTF-8';
}

$rss->stripHTML = TRUE;

if (!empty($rss_cfg['feeds'])) {
    foreach ($rss_cfg['feeds'] as $feed_url) {
        if (($feed = $rss->get($feed_url)) !== FALSE) {
            $i = 2;
            $result = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
            foreach($feed['items'] as $id => $item) {
                if (empty($item['title'])) {
                    $item['title'] = $item['desc'];
                }
                $item['title'] = mb_substr($item['title'], 0, $rss_cfg['title-length']).((mb_strlen($item['title']) > $rss_cfg['title-length']) ? '...' : '');
                $item['desc']  = mb_substr($item['desc'],  0, $rss_cfg['desc-length']).((mb_strlen($item['desc'])   > $rss_cfg['desc-length']) ? '...' : '');
                $result .= '<tr><td class="row'.$i.'"><a href="'.$rss->unHtmlEntities($item['link']).'"><abbr title="'.$item['desc'].'">'.$item['title'].' </abbr></a></td></tr>';
                $i++;
                if ($i > 3) $i = 2;
            }
            $result .= '</table>';
            $title = (!empty($feed['link']) ? '<a href="'.$feed['link'].'">'.(!empty($feed['title']) ? $feed['title'] : __('RSS Feed')).'</a>' : (!empty($feed['title']) ? $feed['title'] : __('RSS Feed')));
            ShowWindow($title, $result);
        }
    }
}
