<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$sections = CMS::call('POSTS')->getSections();

if (empty($sections)) Redirect('posts');

unset($sections['drafts']);

$section  = FILTER::get('REQUEST', 'section');

if ($section === 'drafts') Redirect('posts');      # Wrong section request

$category = intval(FILTER::get('REQUEST', 'category'));
$post     = intval(FILTER::get('REQUEST', 'item'));

if (!empty($post) && !empty($category) && !empty($section)) {
    $categories = CMS::call('POSTS')->getCategories($section);
    if ($categories === FALSE) {
        Redirect('posts');      # Wrong section request
    }

    $content = CMS::call('POSTS')->getContent($category);
    if (($content === FALSE) || empty($content[$post])) {
        Redirect('posts', $section);        # Wrong category or post request
    }

    $comments = CMS::call('POSTS')->getComments($post);
    $comment  = intval(FILTER::get('REQUEST', 'comment'));

    if (!empty($REQUEST['save'])) {
        # Save new or edited comment
        try {
            # If $comment is empty a new comment will be created
            $result = CMS::call('POSTS')->saveComment($comment, $post);
        } catch (Exception $error) {
            ShowError(__($error->getMessage()));
        }
    } else {
        if (!empty($REQUEST['action'])) {
            switch ($REQUEST['action']) {

                case 'edit':
                    if (!empty($content[$post]['opened'])) {
                        if (!empty($comments[$comment])) {
                            if (USER::moderator('posts', $comments[$comment])) {
                                # For user it is actual only for 5 minits after post
                                $output = [];
                                $output['comment'] = $comment;
                                $output['text'] = empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text'];
                                if (USER::moderator('posts')) {
                                    $output['moderator'] = TRUE;
                                }
                                $output['bbcodes'] = CMS::call('PARSER')->showBbcodesPanel('edit.text', !empty($output['moderator']));
                                $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-edit.tpl');
                                ShowWindow(__('Edit'), $TPL->parse($output));
                            }
                        }
                    } else ShowError(__('Comments are not allowed'));
                    break;

                case 'delete':
                    try {
                        $result = CMS::call('POSTS')->removeComment($comment);
                        $result = ($result > $comment) ? $comment : $result;
                    } catch (Exception $error) {
                        ShowError(__($error->getMessage()));
                    }
                    break;

                case 'close':
                    if (USER::$root) CMS::call('POSTS')->setValue($post, 'opened', FALSE);
                    break;

                case 'open':
                    if (USER::$root) CMS::call('POSTS')->setValue($post, 'opened', TRUE);
                    break;

                case 'ban':
                    if (USER::moderator('posts')) CMS::call('FILTER')->ban();
                    break;

                default:
                    Redirect('posts', $section, $category, $post);
                    break;
            }
        }
    }

    $post = CMS::call('POSTS')->getItem($post);
    SYSTEM::set('pagename', $post['title']);
    SYSTEM::setPageDescription($post['title']);
    SYSTEM::setPageKeywords($post['keywords']);

    $perpage = intval(CONFIG::getValue('posts', 'comments-per-page'));
    if     (!empty($comment)) $page = ceil(intval($comment / $perpage));
    elseif (!empty($result))  $page = ceil(intval($result / $perpage));
    else                      $page = intval(FILTER::get('REQUEST', 'page'));

    # Don't show post, if number of comments > per page
    if ($page < 2) {
        # Show post with full text
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'full.tpl');
        ShowWindow(__('Articles'), $TPL->parse(CMS::call('POSTS')->getItem($post['id'], 'text')));
        CMS::call('POSTS')->incCount($post['id'], 'views');
    }
    # Show comments
    ShowComments('POSTS', $post, $page, $perpage, dirname(__FILE__).DS);

} elseif (!empty($category) && !empty($section)) {
    # Show posts from category
    $categories = CMS::call('POSTS')->getCategories($section);
    if ($categories === FALSE) {
        Redirect('posts');      # Wrong section request
    }

    $content = CMS::call('POSTS')->getContent($category);
    if ($content === FALSE) {
        Redirect('posts', $section);    # Wrong category request
    }

    SYSTEM::set('pagename', $categories[$category]['title']);
    SYSTEM::setPageDescription(__('Posts').' - '.$categories[$category]['title']);
    if (!empty($content)) {
        krsort($content);
        $count = sizeof($content);
        $keys  = array_keys($content);
        $page  = intval(FILTER::get('REQUEST', 'page'));
        $perpage = intval(CONFIG::getValue('posts', 'posts-per-page'));
        $pagination = GetPagination($page, $perpage, $count);
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'short.tpl');
        $output = '';
        for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
            $post = CMS::call('POSTS')->getItem($keys[$i], 'desc');
            $post['comment'] = ($post['comments'] > 0) ? $post['link'].COMMENT.$post['comments'] : $post['link'];
            SYSTEM::setPageKeywords($post['keywords']);
            $output .= $TPL->parse($post);
        }
        ShowWindow($categories[$category]['title'], $output);
        if ($count > $perpage) {
            ShowWindow('', Pagination($count, $perpage, $page, $categories[$category]['link']));
        }
    } else ShowWindow($categories[$category]['title'], __('Database is empty'), 'center');

} elseif (!empty($section)) {
    # Show section with allowed categories and last items
    $output = CMS::call('POSTS')->showSection($section);
    if ($output === FALSE) {
        Redirect('posts');

    } elseif (!empty($output['categories'])) {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
        ShowWindow($output['title'], $TPL->parse($output));

    } else ShowWindow($output['title'], __('Database is empty'), 'center');

} elseif (FILTER::get('REQUEST', 'from') && FILTER::get('REQUEST', 'until'))  {
    SYSTEM::set('pagename', __('Posts').' - '.__('Search results'));
    SYSTEM::setPageDescription(__('Posts').' - '.__('Search results'));
    $from   = FILTER::get('REQUEST', 'from');
    $until  = FILTER::get('REQUEST', 'until');
    $output = '';
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'short.tpl');
    foreach ($sections as $id => $section) {
        $categories = CMS::call('POSTS')->getCategories($id);
        foreach($categories as $key => $category) {
            $content = CMS::call('POSTS')->getContent($key);
            foreach($content as $i => $post) {
                if (($post['time'] >= $from) && ($post['time'] <= $until)) {
                    $post = CMS::call('POSTS')->getItem($i, 'desc');
                    $post['comment'] = ($post['comments'] > 0) ? $post['link'].COMMENT.$post['comments'] : $post['link'];
                    $output .= $TPL->parse($post);
                }
            }
        }
    }
    if (!empty($output))
         ShowWindow(__('Search results'), $output);
    else ShowWindow(__('Search results'), __('Nothing founded'), 'center');

} else {
    # Show allowed sections with allowed categories
    $output = CMS::call('POSTS')->showSections();
    if (!empty($output)) {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'sections.tpl');
        ShowWindow(__('Posts'), $TPL->parse(['sections' => $output]));

    } else ShowWindow(__('Posts'), __('Database is empty'), 'center');
}
