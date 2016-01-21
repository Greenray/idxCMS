<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module: GALLERY

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERY')->getSections();

if (!empty($sections)) {
    $section  = FILTER::get('REQUEST', 'section');
    if (!empty($section)) {
        $category = FILTER::get('REQUEST', 'category');
        $item     = FILTER::get('REQUEST', 'item');
        if (!empty($item) && !empty($category) && !empty($section)) {
            $categories = CMS::call('GALLERY')->getCategories($section);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('gallery'));

            $content = CMS::call('GALLERY')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content || empty($content[$item])) SYSTEM::showMessage('Invalid publicaton', CreateUrl('gallery', $section));

            $comments = CMS::call('GALLERY')->getComments($item);
            $comment  = FILTER::get('REQUEST', 'comment');

            try {
                if (!empty($REQUEST['save'])) {
                    #
                    # If $comment is empty a new comment will be created
                    #
                    $result = CMS::call('GALLERY')->saveComment($comment, $item);
                }

                if (!empty($REQUEST['action'])) {
                    #
                    # Actions is allowed for admin and moderators
                    #
                    if (USER::moderator('gallery', $comments[$comment])) {
                        switch ($REQUEST['action']) {

                            case 'edit':
                                #
                                # Edit comment
                                #
                                if (!empty($content[$item]['opened'])) {
                                    if (!empty($comments[$comment])) {
                                        if (USER::moderator('gallery', $comments[$comment])) {
                                            #
                                            # For user it is actual only for 5 minits after post
                                            #
                                            $TPL = new TEMPLATE(__DIR__.DS.'comment-edit.tpl');
                                            $TPL->set('comment',   $comment);
                                            $TPL->set('text',      empty($REQUEST['text']) ? $comments[$comment]['text'] : $REQUEST['text']);
                                            $TPL->set('moderator', USER::moderator('gallery') ? TRUE : NULL);
                                            $TPL->set('bbcodes',   CMS::call('PARSER')->showBbcodesPanel('edit.text', USER::moderator('gallery')));
                                            SYSTEM::defineWindow('Edit', $TPL->parse());
                                        }
                                    }
                                } else SYSTEM::showError('Comments are not allowed', CreateUrl('catalogs', $section, $category, $item));
                                break;

                            case 'delete':
                                #
                                # Remove comment
                                #
                                $result = CMS::call('GALLERY')->removeComment($comment);
                                $result = ($result > $comment) ? $comment : $result;
                                break;

                            case 'close':
                                #
                                # Close post for commenting
                                #
                                if (USER::$root) CMS::call('GALLERY')->setValue($item, 'opened', FALSE);
                                break;

                            case 'open':
                                #
                                # Open item for commenting
                                #
                                if (USER::$root) CMS::call('GALLERY')->setValue($item, 'opened', TRUE);
                                break;

                            case 'ban':
                                #
                                # Ban bad user
                                #
                                if (USER::moderator('gallery')) CMS::call('FILTER')->ban();
                                break;
                        }
                    }
                }

            } catch (Exception $error) {
                SYSTEM::showError($error->getMessage());
            }

            $item = CMS::call('GALLERY')->getItem($item);
            #
            # Wrong item request
            #
            if (!$item) SYSTEM::showError('Invalid query', CreateUrl('gallery', $section, $category));

            SYSTEM::set('pagename', $item['title']);
            SYSTEM::setPageDescription($item['title']);
            SYSTEM::setPageKeywords($item['keywords']);

            $perpage = CONFIG::getValue('gallery', 'comments_per_page');
            if     (!empty($comment)) $page = ceil($comment / $perpage);
            elseif (!empty($result))  $page = ceil($result / $perpage);
            else                      $page = FILTER::get('REQUEST', 'page');
            #
            # Don't show image, if number of comments > per page
            #
            if ($page < 2) {
                #
                # Show image with full text
                #
                $TPL = new TEMPLATE(__DIR__.DS.'full.tpl');
                $TPL->set(CMS::call('GALLERY')->getItem($item['id'], 'text'));
                $TPL->set('module', 'gallery');
                SYSTEM::defineWindow($categories[$category]['title'], $TPL->parse());
                CMS::call('GALLERY')->incCount($item['id'], 'views');
            }
            #
            # Show comments
            #
            CMS::call('GALLERY')->showComments($item, $page, $perpage, __DIR__.DS);

        } elseif (!empty($category) && !empty($section)) {
            #
            # Show items from category
            #
            $categories = CMS::call('GALLERY')->getCategories($section);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('gallery'));

            $content    = CMS::call('GALLERY')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content) SYSTEM::showMessage('Category is empty', CreateUrl('gallery', $section));

            SYSTEM::set('pagename', $categories[$category]['title']);
            SYSTEM::setPageDescription(__('Gallery').' - '.$categories[$category]['title']);

            $output  = '';
            $count   = sizeof($content);
            $keys    = array_keys($content);
            $page    = FILTER::get('REQUEST', 'page');
            $width   = CONFIG::getValue('main', 'thumb_width');
            $height  = CONFIG::getValue('main', 'thumb-height');
            $images  = [];
            $showed  = 0;
            $perpage = 9;
            $pagination = GetPagination($page, $perpage, $count);

            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $item = CMS::call('GALLERY')->getImage($keys[$i]);
                $item['date']    = FormatTime('d F Y', $item['time']).' '.__('year');
                $item['path']    = $categories[$category]['path'];
                $item['width']   = $width;
                $item['height']  = $height;
                $item['comment'] = ($item['comments'] > 0) ? $item['link'].COMMENT.$item['comments'] : $item['link'];
                SYSTEM::setPageKeywords($item['keywords']);
                $images[] = $item;
    //            if (($i === 2) || ($i === 5)) {
    //                $TPL->set('images', $images);
    //                $output .= $TPL->parse(__DIR__.DS.'images.tpl');
    //                $images = [];
    //            }
                ++$showed;
            }

            $TPL = new TEMPLATE(__DIR__.DS.'images.tpl');
            $TPL->set('images', $images);
            $output .= $TPL->parse();
            if ($showed !== $perpage) {
                for ($showed; $showed < $perpage; $showed++) {
                    $images[] = [];
                    if (($showed === 2) || ($showed === 5)) {
                        $images = [];
                    }
                }
            }
            SYSTEM::defineWindow($categories[$category]['title'], $output);

            if ($count > $perpage) {
                SYSTEM::defineWindow('', Pagination($count, $perpage, $page, $categories[$category]['link']));
            }

        } else {
            #
            # Show section with allowed categories and last items
            #
            $output = CMS::call('GALLERY')->showSection($section);
            #
            # Empty section request
            #
            if (!$output) SYSTEM::showMessage('Section is empty', MODULE.'gallery');


            $TPL = new TEMPLATE(__DIR__.DS.'categories.tpl');
            $TPL->set('categories', $output['categories']);
            SYSTEM::defineWindow($output['title'], $TPL->parse());
        }

    } else {
        #
        # Show allowed sections with allowed categories
        #
        $output = CMS::call('GALLERY')->showSections();
        if (empty($output)) SYSTEM::showMessage('Database is empty', MODULE.'index');

        $TPL = new TEMPLATE(__DIR__.DS.'sections.tpl');
        $TPL->set('sections', $output);
        SYSTEM::defineWindow('Gallery', $TPL->parse());
    }
} else SYSTEM::showMessage('Database is empty', CreateUrl('index'));
