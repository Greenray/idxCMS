<?php
# idxCMS Flat Files Content Management Sysytem
# Module Search
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$config = CONFIG::getSection('search');

if (USER::loggedIn() || $config['allow-guest']) {
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
                    if (!empty($sections['drafts'])) {
                        unset($sections['drafts']);
                    }
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
                     array(
                        'min'  => $config['query-min'],
                        'max'  => $config['query-max'],
                        'find' => $word
                    )
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
            $i = 1;
            foreach ($show as $link => $text) {
                $parts = explode('|', $text);
                $results['result'][$i]['link']  = $link;
                $results['result'][$i]['title'] = $parts[1];
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
