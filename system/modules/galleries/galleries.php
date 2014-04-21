<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE GALLERIES

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERIES')->getSections();

if ($sections === FALSE) {
    Redirect('galleries');
}

if (empty($sections)) {
    ShowWindow(__('Galleries'), __('Database is empty'), 'center');
} else {
    $section = FILTER::get('REQUEST', 'section');
    if ($section === FALSE) {
        Redirect('galleries');      # Wrong section request
    }
    $category = (int) FILTER::get('REQUEST', 'category');
    $item     = (int) FILTER::get('REQUEST', 'item');
    if (!empty($item) && !empty($category) && !empty($section)) {
        $categories = CMS::call('GALLERIES')->getCategories($section);
        if ($categories === FALSE) {
            Redirect('galleries');      # Wrong section request
        }
        $content = CMS::call('GALLERIES')->getContent($category);
        if (($content === FALSE) || empty($content[$item])) {
            Redirect('galleries', $section);        # Wrong category or post request
        }
        $comments = CMS::call('GALLERIES')->getComments($item);
        $comment  = (int) FILTER::get('REQUEST', 'comment');
        if (!empty($REQUEST['save'])) {
            try {
                # If $comment is empty a new comment will be created
                $result = CMS::call('GALLERIES')->saveComment($comment, $item);
            } catch (Exception $error) {
                ShowError(__($error->getMessage()));
            }
        } else {
            if (!empty($REQUEST['action'])) {
                switch ($REQUEST['action']) {
                    case 'edit':
                        if (!empty($content[$item]['opened'])) {
                            if (!empty($comments[$comment])) {
                                if (USER::moderator('galleries', $comments[$comment])) {
                                    # For user it is actual only for 5 minits after post
                                    $output = array();
                                    $output['comment'] = $comment;
                                    $output['text'] = empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text'];
                                    if (USER::moderator('galleries')) {
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
                            $result = CMS::call('GALLERIES')->removeComment($comment);
                            $result = ($result > $comment) ? $comment : $result;
                        } catch (Exception $error) {
                            ShowError(__($error->getMessage()));
                        }
                        break;
                    case 'close':
                        if (CMS::call('USER')->checkRoot()) {
                            CMS::call('GALLERIES')->setValue($item, 'opened', FALSE);
                        }
                        break;
                    case 'open':
                        if (CMS::call('USER')->checkRoot()) {
                            CMS::call('GALLERIES')->setValue($item, 'opened', TRUE);
                        }
                        break;
                    case 'ban':
                        if (USER::moderator('galleries')) {
                            CMS::call('FILTER')->ban();
                        }
                        break;
                    default:
                        Redirect('galleries', $section, $category, $item);
                        break;
                }
            }
        }
        $item = CMS::call('GALLERIES')->getItem($item);
        SYSTEM::set('pagename', $item['title']);
        SYSTEM::setPageDescription($item['title']);
        SYSTEM::setPageKeywords($item['keywords']);
        $perpage = (int) CONFIG::getValue('galleries', 'comments-per-page');
        if (!empty($comment)) {
             $page = ceil((int)$comment / $perpage);
        } elseif (!empty($result)) {
            $page = ceil((int)$result / $perpage);
        } else {
            $page = (int) FILTER::get('REQUEST', 'page');
        }
        # Don't show image, if number of comments > per page
        if ($page < 2) {
            # Show image with full text
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'full.tpl');
            ShowWindow(
                $categories[$category]['title'],
                $TPL->parse(
                    CMS::call('GALLERIES')->getItem($item['id'], 'text')
                )
            );
            CMS::call('GALLERIES')->incCount($item['id'], 'views');
        }
        # Show comments
        $comments = CMS::call('GALLERIES')->getComments($item['id']);
        if (!empty($comments)) {
            $count  = sizeof($comments);
            $items  = array_keys($comments);
            $output = '';
            $pagination = GetPagination($page, $perpage, $count);
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment.tpl');
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $output .= $TPL->parse(CMS::call('GALLERIES')->getComment($items[$i], $page));
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
                            'comment-length' => CONFIG::getValue('galleries', 'comment-length'),
                        )
                    )
                );
            }
        }
    } elseif (!empty($category) && !empty($section)) {
        # Show items from category
        $categories = CMS::call('GALLERIES')->getCategories($section);
        if ($categories === FALSE) {
            Redirect('galleries');      # Wrong section request
        }
        $content = CMS::call('GALLERIES')->getContent($category);
        if ($content === FALSE) {
            Redirect('galleries', $section);    # Wrong category request
        }
        SYSTEM::set('pagename', $categories[$category]['title']);
        SYSTEM::setPageDescription(__('Galleries').' - '.$categories[$category]['title']);
        if (!empty($content)) {
            $count  = sizeof($content);
            $keys    = array_keys($content);
            $page    = (int) FILTER::get('REQUEST', 'page');
            $width   = CONFIG::getValue('main', 'thumb-width');
            $height  = CONFIG::getValue('main', 'thumb-height');
            $images  = array();
            $showed  = 0;
            $perpage = 9;
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'images.tpl');
            $pagination = GetPagination($page, $perpage, $count);
            $output = '';
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $item = CMS::call('GALLERIES')->getImage($keys[$i]);
                $item['date'] = FormatTime('d F Y', $item['time']).' '.__('year');
                $item['path'] = $categories[$category]['path'];
                $item['width']  = $width;
                $item['height'] = $height;
                $item['comment'] = ($item['comments'] > 0) ? $item['link'].COMMENT.$item['comments'] : $item['link'];
                SYSTEM::setPageKeywords($item['keywords']);
                $images[] = $item;
                if (($i === 2) || ($i === 5)) {
                    $output .= $TPL->parse(array('images' => $images));
                    $images = array();
                }
                ++$showed;
            }
            $output .= $TPL->parse(array('images' => $images));
            if ($showed !== $perpage) {
                for ($showed; $showed < $perpage; $showed++) {
                    $images[] = array();
                    if (($showed === 2) || ($showed === 5)) {
                        $images = array();
                    }
                }
            }
            ShowWindow($categories[$category]['title'], $output);
            if ($count > $perpage) {
                ShowWindow('', Pagination($count, $perpage, $page, $categories[$category]['link']));
            }
        } else ShowWindow($categories[$category]['title'], __('Database is empty'), 'center');
    } elseif (!empty($section)) {
        # Show section with allowed categories and last items
        $output = CMS::call('GALLERIES')->showSection($section);
        if ($output === FALSE) {
            Redirect('galleries');
        } elseif (!empty($output['categories'])) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
            ShowWindow($output['title'], $TPL->parse($output));
        } else {
            ShowWindow($output['title'], __('Database is empty'), 'center');
        }
    } else {
        # Show allowed sections with allowed categories
        $output = CMS::call('GALLERIES')->showSections();
        if (!empty($output)) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'sections.tpl');
            ShowWindow(__('Galleries'), $TPL->parse(array('sections' => $output)));
        } else {
            ShowWindow(__('Galleries'), __('Database is empty'), 'center');
        }
    }
}
?>