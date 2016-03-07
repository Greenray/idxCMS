<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module RSS

if (!defined('idxCMS')) die();

$rss_cfg = CONFIG::getSection('rss-aggregator');
$RSS     = new RSS_AGGREGATOR();
$RSS->cache_time  = $rss_cfg['cache-time'];
$RSS->cache_dir   = CONTENT.'rss-cache';
$RSS->items_limit = CONFIG::getValue('main', 'last');

if (function_exists('iconv')) {
    $RSS->default_cp = 'UTF-8';
    $RSS->cp = 'UTF-8';
}

$RSS->stripHTML = TRUE;

if (!empty($rss_cfg['feeds'])) {

    foreach ($rss_cfg['feeds'] as $feed_url) {
        if (($feed = $RSS->get($feed_url)) !== FALSE) {
            $i = 2;
            $result = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';

            foreach($feed['items'] as $id => $item) {
                if (empty($item['title'])) {
                    $item['title'] = $item['desc'];
                }

                $item['title'] = mb_substr($item['title'], 0, $rss_cfg['title_length']).((mb_strlen($item['title']) > $rss_cfg['title_length']) ? '...' : '');
                $item['desc']  = mb_substr($item['desc'],  0, $rss_cfg['desc_length']).((mb_strlen($item['desc'])   > $rss_cfg['desc_length']) ? '...' : '');
                $result .= '<tr><td class="row'.$i.'"><a href="'.$RSS->unHtmlEntities($item['link']).'"><abbr title="'.$item['desc'].'">'.$item['title'].' </abbr></a></td></tr>';
                $i++;

                if ($i > 3) $i = 2;
            }

            $result .= '</table>';
            $title = (!empty($feed['link']) ? '<a href="'.$feed['link'].'">'.(!empty($feed['title']) ? $feed['title'] : __('RSS Feed')).'</a>' : (!empty($feed['title']) ? $feed['title'] : __('RSS Feed')));
            SYSTEM::defineWindow($title, $result);
        }
    }
}
