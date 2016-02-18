<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module CATALOGS

if (!defined('idxCMS')) die();

$sections = CMS::call('CATALOGS')->getSections();

if (!empty($sections)) {
    $section  = FILTER::get('REQUEST', 'section');
    if (!empty($section)) {
        $category = FILTER::get('REQUEST', 'category');
        $item     = FILTER::get('REQUEST', 'item');
        if (!empty($item) && !empty($category) && !empty($section)) {
            $categories = CMS::call('CATALOGS')->getCategories($section);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('catalogs'));

            $content = CMS::call('CATALOGS')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content || empty($content[$item])) SYSTEM::showMessage('Invalid publicaton', CreateUrl('catalogs', $section));

            $comments = CMS::call('CATALOGS')->getComments($item);
            $comment  = FILTER::get('REQUEST', 'comment');

            try {
                if (!empty($REQUEST['save'])) {
                    #
                    # If $comment is empty a new comment will be created
                    #
                    $result = CMS::call('CATALOGS')->saveComment($comment, $item);
                }

                if (!empty($REQUEST['get'])) {
                    #
                    # Download file
                    #
                    CMS::call('CATALOGS')->incCount($item, 'downloads');
                    $file = empty($content[$item]['file']) ? $content[$item]['music'] : $content[$item]['file'];
                    header('Location: '.CATALOGS.$section.DS.$category.DS.$item.DS.$file);
                    die();
                }

                if (!empty($REQUEST['go'])) {
                    #
                    # Jumplink
                    #
                    CMS::call('CATALOGS')->incCount($item, 'clicks');
                    header('Location: '.$content[$item]['site']);
                    die();
                }

                if (!empty($REQUEST['action'])) {
                    #
                    # Actions is allowed for admin and moderators
                    #
                    if (USER::moderator('catalogs', $comments[$comment])) {
                        switch ($REQUEST['action']) {

                        case 'edit':
                            #
                            # Edit comment
                            #
                            if (!empty($content[$item]['opened'])) {
                                if (!empty($comments[$comment])) {
                                    #
                                    # For user it is actual only for 5 minits after post
                                    #
                                    $TPL = new TEMPLATE(__DIR__.DS.'comment-edit.tpl');
                                    $TPL->set('comment', $comment);
                                    $TPL->set('text', empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text']);
                                    $TPL->set('moderator', USER::moderator('catalogs') ? TRUE : NULL);
                                    $TPL->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('edit.text', USER::moderator('catalogs')));
                                    SYSTEM::defineWindow('Edit', $TPL->parse());
                                }
                            } else SYSTEM::showError('Comments are not allowed', CreateUrl('catalogs', $section, $category, $item));
                            break;

                        case 'delete':
                            #
                            # Remove comment
                            #
                            $result = CMS::call('CATALOGS')->removeComment($comment);
                            $result = ($result > $comment) ? $comment : $result;
                            break;

                        case 'close':
                            #
                            # Close item for commenting
                            #
                            if (USER::$root) CMS::call('CATALOGS')->setValue($item, 'opened', FALSE);
                            break;

                        case 'open':
                            #
                            # Open item for commenting
                            #
                            if (USER::$root) CMS::call('CATALOGS')->setValue($item, 'opened', TRUE);
                            break;

                        case 'ban':
                            #
                            # Ban bad user
                            #
                            if (USER::moderator('catalogs')) CMS::call('FILTER')->ban();
                            break;
                        }
                    }
                }
            } catch (Exception $error) {
                SYSTEM::showError($error->getMessage());
            }

            $item = CMS::call('CATALOGS')->getItem($item);
            #
            # Wrong item request
            #
            if (!$item) SYSTEM::showError('Invalid query', CreateUrl('catalogs', $section, $category));

            SYSTEM::set('pagename', $item['title']);
            SYSTEM::setPageDescription($item['title']);
            SYSTEM::setPageKeywords($item['keywords']);

            $perpage = CONFIG::getValue('catalogs', 'comments_per_page');
            if (!empty($comment))    $page = ceil($comment / $perpage);
            elseif (!empty($result)) $page = ceil($result / $perpage);
            else                     $page = FILTER::get('REQUEST', 'page');
            #
            # Don't show item, if number of comments > per page
            #
            if ($page < 2) {
                #
                # Show full item
                #
                $output = CMS::call('CATALOGS')->getItem($item['id'], 'text');
                $output['module'] = 'catalogs';
                if ($section === 'music') {
                    $output = array_merge($output, CONFIG::getSection('audio'));
                }

                $TPL = new TEMPLATE(__DIR__.DS.'full.tpl');
                $TPL->set($output);
                SYSTEM::defineWindow($categories[$category]['title'], $TPL->parse());
                CMS::call('CATALOGS')->incCount($item['id'], 'views');
            }
            #
            # Show comments
            #
            CMS::call('CATALOGS')->showComments($item, $page, $perpage, __DIR__.DS);

        } elseif (!empty($category) && !empty($section)) {
            #
            # Show items from category
            #
            $categories = CMS::call('CATALOGS')->getCategories($section);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('catalogs'));

            $content = CMS::call('CATALOGS')->getContent($category);
            #
            # Wrong category or item request
            #
            if (empty($content)) SYSTEM::showMessage('Category is empty', CreateUrl('catalogs', $section));

            SYSTEM::set('pagename', $categories[$category]['title']);
            SYSTEM::setPageDescription(__('Catalogs').' - '.$categories[$category]['title']);
            krsort($content);

            $TPL    = new TEMPLATE(__DIR__.DS.'short.tpl');
            $output = '';
            $count  = sizeof($content);
            $keys   = array_keys($content);
            $page   = FILTER::get('REQUEST', 'page');
            $perpage    = CONFIG::getValue('catalogs', 'items_per_page');
            $pagination = GetPagination($page, $perpage, $count);

            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $item = CMS::call('CATALOGS')->getItem($keys[$i], 'desc');
                $TPL->set($item);
                SYSTEM::setPageKeywords($item['keywords']);
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
            $output = CMS::call('CATALOGS')->showSection($section);
            #
            # Empty section request
            #
            if (!$output) SYSTEM::showMessage('Section is empty', MODULE.'catalogs');

            $TPL = new TEMPLATE(__DIR__.DS.'categories.tpl');
            $TPL->set('categories', $output['categories']);
            SYSTEM::defineWindow($output['title'], $TPL->parse());
        }
    } else {
        #
        # Show allowed sections with allowed categories for user
        #
        $output = CMS::call('CATALOGS')->showSections();
        if (empty($output)) SYSTEM::showMessage('Database is empty', MODULE.'index');

        $TPL = new TEMPLATE(__DIR__.DS.'sections.tpl');
        $TPL->set('sections', $output);
        SYSTEM::defineWindow('Catalogs', $TPL->parse());
    }
} else SYSTEM::showMessage('Database is empty', CreateUrl('index'));
