<?php
# idxCMS Flat Files Content Management Sysytem
# Module Search
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$config = CONFIG::getSection('search');

if (USER::$logged_in || $config['allow-guest']) {
    if (!empty($REQUEST['search'])) {
        $items = explode(" ", $REQUEST['search']);
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'results.tpl');
        $ERR = new TEMPLATE(dirname(__FILE__).DS.'error.tpl');
        $founded = [];
        $common  = [];
        $output  = '';
        foreach ($items as $word) {
            $founded[$word] = [];
            $length = mb_strlen($word, 'UTF-8');
            if (($length >= $config['query-min']) && ($length <= $config['query-max'])) {
                $searchs = SYSTEM::get('search');
                foreach($searchs as $module) {
                    $obj = strtoupper($module);
                    $sections = CMS::call($obj)->getSections();
                    unset($sections['drafts']);

                    $result = [];
                    if (!empty($sections)) {
                        foreach ($sections as $id => $section) {
                            $categories = CMS::call($obj)->getCategories($id);
                            if (!empty($categories)) {
                                foreach ($categories as $key => $category) {
                                    $content = CMS::call($obj)->getContent($key);
                                    if (!empty($content)) {
                                        foreach ($content as $i => $item) {
                                            $item = CMS::call($obj)->getItem($i, 'full', FALSE);
                                            if (!empty($item['keywords'])) {
                                                SearchResult($item['keywords'], $item['title'], $REQUEST['search'], $item['link'], $result);
                                            }
                                            SearchResult($item['title'], $item['title'], $REQUEST['search'], $item['link'], $result);
                                            SearchResult($item['nick'], $item['title'], $REQUEST['search'], $item['link'], $result);
                                            if (!empty($item['desc'])) {
                                                SearchResult($item['desc'], $item['title'], $REQUEST['search'], $item['link'], $result);
                                            }
                                            SearchResult($item['text'], $item['title'], $REQUEST['search'], $item['link'], $result);
                                        }
                                    }
                                }
                            }
                        }
                        $founded[$word] = $result;
                        if (!empty($founded[$word])) $common = array_merge($common, $founded[$word]);
                    }
                }
            } else {
                $output .= $ERR->parse(
                    [
                        'min'  => $config['query-min'],
                        'max'  => $config['query-max'],
                        'find' => $word
                    ]
                );
            }
        }
        unset($ERR);
        $results = [];
        $results['count'] = sizeof($common);
        $perpage = (int) CONFIG::getValue('search', 'per-page');
        $page    = (int) FILTER::get('REQUEST', 'page');
        $pagination = GetPagination($page, $perpage, $results['count']);
        if (!empty($common)) {
            $show = array_slice($common, $pagination['start'], $perpage, TRUE);
            $i    = 1;
            foreach ($show as $link => $text) {
                $parts = explode('|', $text);
                $results['result'][$i]['link']  = $link;
                $results['result'][$i]['title'] = $parts[1];
                $strlen = mb_strlen($parts[2]);
                $real   = mb_substr(stristr($parts[2], $parts[0]), 0, mb_strlen($parts[0]));
                $start  = 0;
                $start_ = '';
                $end_   = '';
                $temp   = stripos($parts[2], $parts[0]);
                if ($temp > ($config['block'] / 2)) {
                    $start  = $temp - ($config['block'] / 2);
                    $start_ = '...';
                }
                if (($strlen - ($config['block'] / 2)) > $temp) {
                    $strlen = $config['block'] - 1;
                    $end_   = '...';
                }
                $results['result'][$i]['text'] = $start_.str_replace($real, '<u><strong><em>'.$real.'</em></strong></u>', mb_substr($text, $start, $strlen)).$end_;
                $results['result'][$i]['text']  = FormatFound($parts[2], $parts[0], $config['block']);
                ++$i;
            }
        }
        $output .= $TPL->parse($results);
        SYSTEM::set('pagename', __('Search results'));
        ShowWindow(__('Search results'), $output);
        if ($results['count'] > $perpage) {
            ShowWindow('', Pagination($results['count'], $perpage, $page, '?module=search&search='.$REQUEST['search']));
        }
    } else {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'search.tpl');
        ShowWindow(__('Search'), $TPL->parse());
    }
}
