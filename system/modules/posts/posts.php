<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module POSTS

if (!defined('idxCMS')) die();

$sections = CMS::call('POSTS')->getSections();

if (!empty($sections)) {
    $section = FILTER::get('REQUEST', 'section');
    if (!empty($section)) {
        if ($section === 'drafts') Redirect('posts');

        unset($sections['drafts']);

        $category = FILTER::get('REQUEST', 'category');
        $post     = FILTER::get('REQUEST', 'item');

        if (!empty($post) && !empty($category) && !empty($section)) {
            $categories = CMS::call('POSTS')->getCategories($section);
            #
            # Empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('posts'));

            $content = CMS::call('POSTS')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content || empty($content[$post])) SYSTEM::showMessage('Invalid publicaton', CreateUrl('posts', $section));

            $comments = CMS::call('POSTS')->getComments($post);
            $comment  = FILTER::get('REQUEST', 'comment');

            try {
                if (!empty($REQUEST['save'])) {
                    #
                    # Save new or edited comment
                    # If $comment is empty a new comment will be created
                    #
                    $result = CMS::call('POSTS')->saveComment($comment, $post);

                }

                if (!empty($REQUEST['action'])) {
                    #
                    # Actions is allowed for admin and moderators
                    #
                    if (USER::moderator('posts', $comments[$comment])) {
                        switch ($REQUEST['action']) {

                            case 'edit':
                                #
                                # Edit comment
                                #
                                if (!empty($content[$post]['opened'])) {
                                    if (!empty($comments[$comment])) {
                                        $moderator = USER::moderator('posts', $comments[$comment]);
                                        if ($moderator) {
                                            #
                                            # For user it is actual only for 5 minits after post
                                            #
                                            $TPL = new TEMPLATE(__DIR__.DS.'comment-edit.tpl');
                                            $TPL->set('comment', $comment);
                                            $TPL->set('text', empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text']);
                                            $TPL->set('moderator', TRUE);
                                            $TPL->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('edit.text', $moderator));
                                            SYSTEM::defineWindow('Edit', $TPL->parse());
                                        }
                                    }
                                } else SYSTEM::showError('Comments are not allowed', CreateUrl('posts', $section, $category, $post));
                                break;

                            case 'delete':
                                #
                                # Remove comment
                                #
                                $result = CMS::call('POSTS')->removeComment($comment);
                                $result = ($result > $comment) ? $comment : $result;
                                break;

                            case 'close':
                                #
                                # Close post for commenting
                                #
                                if (USER::$root) CMS::call('POSTS')->setValue($post, 'opened', FALSE);
                                break;

                            case 'open':
                                #
                                # Open post for commenting
                                #
                                if (USER::$root) CMS::call('POSTS')->setValue($post, 'opened', TRUE);
                                break;

                            case 'ban':
                                #
                                # Ban bad user
                                #
                                if (USER::moderator('posts')) CMS::call('FILTER')->ban();
                                break;
                        }
                    }
                }

            } catch (Exception $error) {
                SYSTEM::showError($error->getMessage());
            }

            $post = CMS::call('POSTS')->getItem($post);
            #
            # Wrong post request
            #
            if (!$post) SYSTEM::showError('Invalid article', CreateUrl('posts', $section, $category));

            SYSTEM::set('pagename', $post['title']);
            SYSTEM::setPageDescription($post['title']);
            SYSTEM::setPageKeywords($post['keywords']);

            $perpage = CONFIG::getValue('posts', 'comments_per_page');
            if     (!empty($comment)) $page = ceil($comment / $perpage);
            elseif (!empty($result))  $page = ceil($result / $perpage);
            else                      $page = FILTER::get('REQUEST', 'page');
            #
            # Don't show post, if number of comments > per page
            #
            if ($page < 2) {
                #
                # Show post with full text
                #
                $TPL = new TEMPLATE(__DIR__.DS.'full.tpl');
                $TPL->set(CMS::call('POSTS')->getItem($post['id'], 'text'));
                $TPL->set('module', 'posts');

                SYSTEM::defineWindow('Articles', $TPL->parse());
                CMS::call('POSTS')->incCount($post['id'], 'views');
            }
            #
            # Show comments
            #
            CMS::call('POSTS')->showComments($post, $page, $perpage, __DIR__.DS);

        } elseif (!empty($category) && !empty($section)) {
            #
            # Show posts from category
            #
            $categories = CMS::call('POSTS')->getCategories($section);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('posts'));

            $content = CMS::call('POSTS')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content) SYSTEM::showMessage('Category is empty', CreateUrl('posts', $section));

            SYSTEM::set('pagename', $categories[$category]['title']);
            SYSTEM::setPageDescription(__('Posts').' - '.$categories[$category]['title']);
            krsort($content);

            $TPL    = new TEMPLATE(__DIR__.DS.'short.tpl');
            $output = '';
            $count  = sizeof($content);
            $keys   = array_keys($content);
            $page   = FILTER::get('REQUEST', 'page');
            $perpage    = CONFIG::getValue('posts', 'posts_per_page');
            $pagination = GetPagination($page, $perpage, $count);
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $post = CMS::call('POSTS')->getItem($keys[$i], 'desc');
                $TPL->set($post);
                SYSTEM::setPageKeywords($post['keywords']);
                $output .= $TPL->parse();
            }

            SYSTEM::defineWindow($categories[$category]['title'], $output);
            if ($count > $perpage) {
                SYSTEM::defineWindow('', Pagination($count, $perpage, $page, $categories[$category]['link']));
            }

        } else {
            #
            # Show section with allowed categories and last items
            #
            $output = CMS::call('POSTS')->showSection($section);
            #
            # Empty section request
            #
            if (!$output) SYSTEM::showMessage('Section is empty', MODULE.'posts');

            $TPL = new TEMPLATE(__DIR__.DS.'categories.tpl');
            $TPL->set($output);
            SYSTEM::defineWindow($output['title'], $TPL->parse());
        }

    } elseif ((($from = FILTER::get('REQUEST', 'from')) !== FALSE) && (($until = FILTER::get('REQUEST', 'until')) !== FALSE))  {
        SYSTEM::set('pagename', __('Posts').' - '.__('Search results'));
        SYSTEM::setPageDescription(__('Posts').' - '.__('Search results'));
        $output = '';
        $TPL = new TEMPLATE(__DIR__.DS.'short.tpl');
        foreach ($sections as $id => $section) {
            $categories = CMS::call('POSTS')->getCategories($id);
            foreach($categories as $key => $category) {
                $content = CMS::call('POSTS')->getContent($key);
                foreach($content as $i => $post) {
                    if (($post['time'] >= $from) && ($post['time'] <= $until)) {
                        $post = CMS::call('POSTS')->getItem($i, 'desc');
                        $post['comment'] = ($post['comments'] > 0) ? $post['link'].COMMENT.$post['comments'] : $post['link'];
                        $TPL->set($post);
                        $output .= $TPL->parse();
                    }
                }
            }
        }
        if (!empty($output))
             SYSTEM::defineWindow('Search results', $output);
        else SYSTEM::defineWindow('Search results', __('Nothing founded'));
    } else {
        #
        # Show allowed sections with allowed categories
        #
        $output = CMS::call('POSTS')->showSections();
        if (empty($output)) SYSTEM::showMessage('Database is empty', CreateUrl('index'));

        $TPL = new TEMPLATE(__DIR__.DS.'sections.tpl');
        $TPL->set('sections', $output);
        SYSTEM::defineWindow('Posts', $TPL->parse());
    }
} else SYSTEM::showMessage('Database is empty', CreateUrl('index'));
