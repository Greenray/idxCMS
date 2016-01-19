<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module SEARCH

if (!defined('idxCMS')) die();

$config = CONFIG::getSection('search');

if (USER::$logged_in || $config['allow_guest']) {
    if (!empty($REQUEST['search'])) {

        $TPL = new TEMPLATE(__DIR__.DS.'results.tpl');

        $items   = explode(' ', $REQUEST['search']);
        $founded = [];
        $common  = [];
        $output  = '';

        foreach ($items as $word) {
            $founded[$word] = [];
            $length = mb_strlen($word, 'UTF-8');

            if (($length >= $config['query_min']) && ($length <= $config['query_max'])) {
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
                                                SearchResult(
                                                    $item['keywords'],
                                                    $item['title'],
                                                    $REQUEST['search'],
                                                    $item['link'],
                                                    $result
                                                );
                                            }
                                            SearchResult(
                                                $item['title'],
                                                $item['title'],
                                                $REQUEST['search'],
                                                $item['link'],
                                                $result
                                            );
                                            SearchResult(
                                                $item['nick'],
                                                $item['title'],
                                                $REQUEST['search'],
                                                $item['link'],
                                                $result
                                            );
                                            if (!empty($item['desc'])) {
                                                SearchResult(
                                                    $item['desc'],
                                                    $item['title'],
                                                    $REQUEST['search'],
                                                    $item['link'],
                                                    $result
                                                );
                                            }
                                            SearchResult(
                                                $item['text'],
                                                $item['title'],
                                                $REQUEST['search'],
                                                $item['link'],
                                                $result
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        $founded[$word] = $result;

                        if (!empty($founded[$word])) {
                            $common = array_merge($common, $founded[$word]);
                        }
                    }
                }
            } else echo SYSTEM::showMessage(__('Input value must be more than').' '.$config['query_min'].' '.__('and less than').' '.$config['query_max'].' '.__('symbols'));
        }

        $results = [];
        $count   = sizeof($common);
        $perpage = CONFIG::getValue('search', 'per_page');
        $page    = FILTER::get('REQUEST', 'page');
        $pagination = GetPagination($page, $perpage, $count);

        if (!empty($common)) {
            $show = array_slice($common, $pagination['start'], $perpage, TRUE);
            $i    = 1;
            foreach ($show as $link => $text) {
                $parts = explode('|', $text);
                $results[$i]['link']  = $link;
                $results[$i]['title'] = $parts[1];
                $results[$i]['text']  = FormatFound($parts[2], $parts[0], $config['block']);
                ++$i;
            }
        }
        $TPL->set('count', $count);
        $TPL->set('results', $results);
        $output .= $TPL->parse();
        SYSTEM::set('pagename', __('Search results'));
        SYSTEM::defineWindow('Search results', $output);

        if ($count > $perpage) {
            SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'search&search='.$REQUEST['search']));
        }
        unset($REQUEST['search']);

    } else {
        $TPL = new TEMPLATE(__DIR__.DS.'search.tpl');
        SYSTEM::defineWindow('Search', $TPL->parse());
    }
}
