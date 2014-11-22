<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE CATALOGS

if (!defined('idxCMS')) die();

$sections = CMS::call('CATALOGS')->getSections();

if ($sections === FALSE) {
    Redirect('catalogs');
}

if (empty($sections)) {
    ShowWindow(__('Catalogs'), __('Database is empty'), 'center');
} else {
    $section = FILTER::get('REQUEST', 'section');
    if ($section === FALSE) {
        Redirect('catalogs');      # Wrong section request
    }
    $category = (int) FILTER::get('REQUEST', 'category');
    $item     = (int) FILTER::get('REQUEST', 'item');
    if (!empty($item) && !empty($category) && !empty($section)) {
        $categories = CMS::call('CATALOGS')->getCategories($section);
        if ($categories === FALSE) {
            Redirect('catalogs');      # Wrong section request
        }
        $content = CMS::call('CATALOGS')->getContent($category);
        if (($content === FALSE) || empty($content[$item])) {
            Redirect('catalogs', $section);        # Wrong category or post request
        }
        if (!empty($REQUEST['get'])) {
            # Download file
            CMS::call('CATALOGS')->incCount($item, 'downloads');
            $file = empty($content[$item]['file']) ? $content[$item]['song'] : $content[$item]['file'];
            header('Location: '.CATALOGS.$section.DS.$category.DS.$item.DS.$file);
            die();
        }
        if (!empty($REQUEST['go'])) {
            CMS::call('CATALOGS')->incCount($item, 'clicks');
            header('Location: '.$content[$item]['site']);
            die();
        }
        $comments = CMS::call('CATALOGS')->getComments($item);
        $comment  = (int) FILTER::get('REQUEST', 'comment');
        if (!empty($REQUEST['save'])) {
            try {
                # If $comment is empty a new comment will be created
                $result = CMS::call('CATALOGS')->saveComment($comment, $item);
            } catch (Exception $error) {
                ShowError(__($error->getMessage()));
            }
        } else {
            if (!empty($REQUEST['action'])) {
                switch ($REQUEST['action']) {
                    case 'edit':
                        if (!empty($content[$item]['opened'])) {
                            if (!empty($comments[$comment])) {
                                if (USER::moderator('catalogs', $comments[$comment])) {
                                    # For user it is actual only for 5 minits after post
                                    $output = array();
                                    $output['comment'] = $comment;
                                    $output['text'] = empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text'];
                                    if (USER::moderator('catalogs')) {
                                        $output['moderator'] = TRUE;
                                    }
                                    $output['bbcodes'] = ShowBbcodesPanel('edit.text', !empty($output['moderator']));
                                    $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-edit.tpl');
                                    ShowWindow(__('Edit'), $TPL->parse($output));
                                }
                            }
                        } else {
                            ShowError(__('Comments are not allowed'));
                        }
                        break;
                    case 'delete':
                        try {
                            $result = CMS::call('CATALOGS')->removeComment($comment);
                            $result = ($result > $comment) ? $comment : $result;
                        } catch (Exception $error) {
                            ShowError(__($error->getMessage()));
                        }
                        break;
                    case 'close':
                        if (CMS::call('USER')->checkRoot()) {
                            CMS::call('CATALOGS')->setValue($item, 'opened', FALSE);
                        }
                        break;
                    case 'open':
                        if (CMS::call('USER')->checkRoot()) {
                            CMS::call('CATALOGS')->setValue($item, 'opened', TRUE);
                        }
                        break;
                    case 'ban':
                        if (USER::moderator('catalogs')) {
                            CMS::call('FILTER')->ban();
                        }
                        break;
                    default:
                        Redirect('catalogs', $section, $category, $item);
                        break;
                }
            }
        }
        $item = CMS::call('CATALOGS')->getItem($item);
        SYSTEM::set('pagename', $item['title']);
        SYSTEM::setPageDescription($item['title']);
        SYSTEM::setPageKeywords($item['keywords']);
        $perpage = (int) CONFIG::getValue('catalogs', 'comments-per-page');
        if (!empty($comment)) {
            $page = ceil((int)$comment / $perpage);
        } elseif (!empty($result)) {
            $page = ceil((int)$result / $perpage);
        } else {
            $page = (int) FILTER::get('REQUEST', 'page');
        }
        # Don't show post, if number of comments > per page
        if ($page < 2) {
            # Show post with full text
            $output = CMS::call('CATALOGS')->getItem($item['id'], 'text');
            if ($section === 'music') {
                $output = array_merge($output, CONFIG::getSection('audio'));
                $output['autostart'] = 'no';
                $output['loop']      = 'no';
            }
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'full.tpl');
            ShowWindow($categories[$category]['title'], $TPL->parse($output));
            CMS::call('CATALOGS')->incCount($item['id'], 'views');
        }
        # Show comments
        $comments = CMS::call('CATALOGS')->getComments($item['id']);
        if (!empty($comments)) {
            $count  = sizeof($comments);
            $items  = array_keys($comments);
            $output = '';
            $pagination = GetPagination($page, $perpage, $count);
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment.tpl');
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $output .= $TPL->parse(CMS::call('CATALOGS')->getComment($items[$i], $page));
            }
            ShowWindow(__('Comments'), $output);
            if ($count > $perpage) {
                ShowWindow('', Pagination($count, $perpage, $page, $item['link']));
            }
        }
        if (USER::loggedIn()) {
            if (!empty($item['opened'])) {
                # Form to post comment
                $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-post.tpl');
                ShowWindow(
                    __('Comment'),
                    $TPL->parse(
                        array(
                            'nickname'       => USER::getUser('nickname'),
                            'not_admin'      => !CMS::call('USER')->checkRoot(),
                            'text'           => FILTER::get('REQUEST', 'text'),
                            'action'         => $item['link'],
                            'bbcodes'        => ShowBbcodesPanel('comment.text'),
                            'comment-length' => CONFIG::getValue('catalogs', 'comment-length'),
                        )
                    )
                );
            }
        }
    } elseif (!empty($category) && !empty($section)) {
        # Show items from category
        $categories = CMS::call('CATALOGS')->getCategories($section);
        if ($categories === FALSE) {
            Redirect('catalogs');      # Wrong section request
        }
        $content = CMS::call('CATALOGS')->getContent($category);
        if ($content === FALSE) {
            Redirect('catalogs', $section);    # Wrong category request
        }
        SYSTEM::set('pagename', $categories[$category]['title']);
        SYSTEM::setPageDescription(__('Catalogs').' - '.$categories[$category]['title']);
        if (!empty($content)) {
            krsort($content);
            $count = sizeof($content);
            $keys  = array_keys($content);
            $page  = (int) FILTER::get('REQUEST', 'page');
            $perpage = (int) CONFIG::getValue('catalogs', 'items-per-page');
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'short.tpl');
            $pagination = GetPagination($page, $perpage, $count);
            $output = '';
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $item = CMS::call('CATALOGS')->getItem($keys[$i], 'desc');
                $item['comment'] = ($item['comments'] > 0) ? $item['link'].COMMENT.$item['comments'] : $item['link'];
                SYSTEM::setPageKeywords($item['keywords']);
                $output .= $TPL->parse($item);
            }
            ShowWindow($categories[$category]['title'], $output);
            if ($count > $perpage) {
                ShowWindow('', Pagination($count, $perpage, $page, $categories[$category]['link']));
            }
        } else {
            ShowWindow($categories[$category]['title'], __('Database is empty'), 'center');
        }
    } elseif (!empty($section)) {
        # Show section with allowed categories and last items
        $output = CMS::call('CATALOGS')->showSection($section);
        if ($output === FALSE) {
            Redirect('catalogs');
        } elseif (!empty($output['categories'])) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
            ShowWindow($output['title'], $TPL->parse($output));
        } else {
            ShowWindow($output['title'], __('Database is empty'), 'center');
        }
    } else {
        # Show allowed sections with allowed categories
        $output = CMS::call('CATALOGS')->showSections();
        if (!empty($output)) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'sections.tpl');
            ShowWindow(__('Catalogs'), $TPL->parse(array('sections' => $output)));
        } else {
            ShowWindow(__('Catalogs'), __('Database is empty'), 'center');
        }
    }
}
?>