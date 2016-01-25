<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module FORUM

if (!defined('idxCMS')) die();

$sections = CMS::call('FORUM')->getSections();

if (!empty($sections)) {
    $section  = FILTER::get('REQUEST', 'section');
    if (!empty($section)) {
        $category = FILTER::get('REQUEST', 'category');
        $topic    = FILTER::get('REQUEST', 'item');

        if (!empty($topic) && !empty($category) && !empty($section)) {
            $categories = CMS::call('FORUM')->getCategories($section);
            #
            # Empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('forum'));

            $content = CMS::call('FORUM')->getContent($category);
            #
            # Wrong category or item request
            #
            if (!$content || empty($content[$topic])) SYSTEM::showMessage('Invalid publicaton', CreateUrl('forum', $section));

            $replies = CMS::call('FORUM')->getComments($topic);
            $reply   = FILTER::get('REQUEST', 'comment');
            #
            # Save reply
            #
            try {
                if (!empty($REQUEST['save'])) {
                    #
                    # Save new or edited comment
                    # If $comment is empty a new comment will be created
                    #
                    $result = CMS::call('FORUM')->saveComment($reply, $topic);
                }

                if (!empty($REQUEST['action'])) {
                    #
                    # Actions is allowed for admin and moderators
                    #
                    if (USER::moderator('forum', $replies[$reply])) {
                        switch ($REQUEST['action']) {

                            case 'edit':
                                #
                                # Edit reply
                                #
                                if (!empty($content[$topic]['opened'])) {
                                    if (empty($reply)) {
                                        #
                                        # Edit topic
                                        #
                                        if (!USER::$logged_in) SYSTEM::showError('Your have no right to edit topic', CreateUrl('forum', $section, $category, $topic));

                                        if (!empty($REQUEST['save'])) {
                                            $topic = CMS::call('FORUM')->saveTopic($topic);
                                            Redirect('forum', $section, $category, $topic);
                                        }
                                        $ed  = CMS::call('FORUM')->getItem($topic, 'text', FALSE);
                                        $TPL = new TEMPLATE(__DIR__.DS.'post.tpl');
                                        $TPL->set('topic', $ed['id']);
                                        $TPL->set('title',  empty($REQUEST['title'])  ? $ed['title']  : $REQUEST['title']);
                                        $TPL->set('text',   empty($REQUEST['text'])   ? $ed['text']   : $REQUEST['text']);
                                        $TPL->set('opened', empty($REQUEST['opened']) ? $ed['opened'] : $REQUEST['opened']);
                                        $TPL->set('pinned', empty($REQUEST['pinned']) ? $ed['pinned'] : $REQUEST['pinned']);
                                        $TPL->set('moderator', USER::moderator('forum', $topic));
                                        $TPL->set('bbCodes', CMS::call('PARSER')->showBbcodesPanel('topic.text'));

                                        SYSTEM::defineWindow('Edit', $TPL->parse());

                                    } else {
                                        if (!empty($replies[$reply])) {
                                            #
                                            # Edit reply
                                            #
                                            if (USER::moderator('forum', $replies[$reply])) {
                                                $TPL = new TEMPLATE(__DIR__.DS.'comment-edit.tpl');
                                                $TPL->set('comment', $reply);
                                                $TPL->set('text', empty($REQUEST['text']) ? $replies[$reply]['text'] : $REQUEST['text']);
                                                $TPL->set('moderator', USER::moderator('forum') ? TRUE : NULL);
                                                $TPL->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('edit.text', !empty($output['moderator'])));

                                                SYSTEM::defineWindow('Edit', $TPL->parse());
                                            }
                                        }
                                    }

                                } else SYSTEM::showMessage('Topic is closed', CreateUrl('forum', $section, $category, $topic));
                                break;

                            case 'delete':
                                #
                                # Remove reply
                                #
                                if (!empty($reply)) {
                                    $result = CMS::call('FORUM')->removeComment($reply);
                                    $reply  = ($result > $reply) ? $reply : $result;
                                } else {
                                    #
                                    # Remove topic
                                    #
                                    if (USER::moderator('forum', $content[$topic])) {
                                        CMS::call('FORUM')->removeItem($topic);
                                        Redirect('forum', $section, $category);
                                    }
                                }
                                break;

                            case 'close':
                                #
                                # Close topic for commenting
                                #
                                if (USER::moderator('forum')) CMS::call('FORUM')->setValue($topic, 'opened', FALSE);
                                break;

                            case 'open':
                                #
                                # Open topic for commenting
                                #
                                if (USER::moderator('forum')) CMS::call('FORUM')->setValue($topic, 'opened', TRUE);
                                break;

                            case 'pin':
                                #
                                # Pin topic
                                #
                                if (USER::moderator('forum')) {
                                    CMS::call('FORUM')->setValue($topic, 'pinned', 1);
                                    CMS::call('FORUM')->sortTopics();
                                }
                                break;

                            case 'unpin':
                                #
                                # Unpin topic
                                #
                                if (USER::moderator('forum')) {
                                    CMS::call('FORUM')->setValue($topic, 'pinned', 0);
                                    CMS::call('FORUM')->sortTopics();
                                }
                                break;

                            case 'ban':
                                #
                                # Ban bad user
                                #
                                if (USER::moderator('forum')) CMS::call('FILTER')->ban();
                                break;
                        }
                    }
                }
            } catch (Exception $error) {
                SYSTEM::showError($error->getMessage());
            }

            $topic = CMS::call('FORUM')->getItem($topic);
            #
            # Wrong post request
            #
            if (!$topic) SYSTEM::showError('Invalid topic', CreateUrl('forum', $section, $category));

            SYSTEM::set('pagename', $topic['title']);
            SYSTEM::setPageDescription($topic['title']);

            $perpage = CONFIG::getValue('forum', 'replies_per_page');
            if     (!empty($reply))  $page = ceil($reply / $perpage);
            elseif (!empty($result)) $page = ceil($result / $perpage);
            else                     $page = FILTER::get('REQUEST', 'page');
            #
            # Don't show topic, if number of comments > per page
            #
            if ($page < 2) {
                #
                # Show topic
                #
                $topic = CMS::call('FORUM')->getItem($topic['id'], 'text');
                $topic['date']    = FormatTime('d F Y H:i:s', $topic['time']);
                $topic['avatar']  = GetAvatar($topic['author']);
                $author = USER::getUserData($topic['author']);
                $topic['stars']   = $author['stars'];
                $topic['status']  = __($author['status']);
                $topic['country'] = $author['country'];
                $topic['city']    = $author['city'];
                if (USER::moderator('forum', $topic)) {
                    $topic['moderator'] = TRUE;
                    if (USER::moderator('forum')) {
                        $topic['admin'] = TRUE;
                        if (!empty($topic['pinned'])) {
                            $topic['command_pin'] = __('Unpin');
                            $topic['action_pin']  = 'unpin';
                        } else {
                            $topic['command_pin'] = __('Pin');
                            $topic['action_pin']  = 'pin';
                        }
                    }
                    if (($author['rights'] === '*') || (USER::getUser('user') === $topic['author'])) {
                        unset($topic['ip']);
                    }
                } else unset($topic['ip']);

                $topic['profile'] = (USER::getUser('user') !== 'guest') ? TRUE : NULL;
                $TPL = new TEMPLATE(__DIR__.DS.'topic.tpl');
                $TPL->set($topic);

                SYSTEM::defineWindow($categories[$category]['title'], $TPL->parse());
                CMS::call('FORUM')->incCount($topic['id'], 'views');
            }
            #
            # Show replies
            #
            CMS::call('FORUM')->showComments($topic, $page, $perpage, __DIR__.DS);

        } elseif (!empty($category) && !empty($section)) {
            #
            # Category request
            #
            $section    = CMS::call('FORUM')->getSection($section);
            $categories = CMS::call('FORUM')->getCategories($section['id']);
            #
            # Wrong or empty section request
            #
            if (!$categories) SYSTEM::showMessage('Section is empty', CreateUrl('forum'));

            $category = CMS::call('FORUM')->getCategory($category);
            $content  = CMS::call('FORUM')->getContent($category['id']);
            #
            # Wrong category or item request
            #
            if (!$content) SYSTEM::showMessage('Category is empty', CreateUrl('posts', $section['id']));

            SYSTEM::set('pagename', $category['title']);
            SYSTEM::setPageDescription(__('Forum').' - '.$category['title']);

            if (!empty($REQUEST['new'])) {
                #
                # New topic
                #
                if (!USER::$logged_in) SYSTEM::showError('You have no right to post topic', CreateUrl('forum', $section['id'], $category['id']));

                if (!empty($REQUEST['save'])) {
                    $result = CMS::call('FORUM')->saveTopic();
                    USER::changeProfileField(USER::getUser('user'), 'topics', '+');
                    Redirect('forum', $section['id'], $category['id'], $result);
                }

                $TPL = new TEMPLATE(__DIR__.DS.'post.tpl');
                $TPL->set('new', TRUE);
                $TPL->set('title',     FILTER::get('REQUEST', 'title'));
                $TPL->set('text',      FILTER::get('REQUEST', 'text'));
                $TPL->set('moderator', FILTER::get('REQUEST', 'moderator'));
                $TPL->set('bbCodes', CMS::call('PARSER')->showBbcodesPanel('topic.text'));

                SYSTEM::defineWindow('New topic', $TPL->parse());

            } else {
                #
                # List of topics from category
                #
                $ids     = array_keys($content);
                $ids     = array_reverse($ids);
                $count   = sizeof($content);
                $page    = FILTER::get('REQUEST', 'page');
                $perpage = CONFIG::getValue('forum', 'topics_per_page');
                $output  = [];
                $pagination = GetPagination($page, $perpage, $count);
                for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                    if (!empty($content[$ids[$i]])) {
                        $output[$ids[$i]] = $content[$ids[$i]];
                        $output[$ids[$i]]['section']  = $section;
                        $output[$ids[$i]]['category'] = $category;
                        $output[$ids[$i]]['link']     = $category['link'].ITEM.$ids[$i];
                        $output[$ids[$i]]['date']     = FormatTime('d m Y', $content[$ids[$i]]['time']);
                        if ($output[$ids[$i]]['comments'] > 0) {
//                    $output[$ids[$i]]['last_link'] = $output[$ids[$i]]['link'].COMMENT.$content[$ids[$i]]['comments'];
                            $replies = CMS::call('FORUM')->getComments($content[$ids[$i]]['id']);
                            $reply   = CMS::call('FORUM')->getComment($ids[$i], 0);
                            $output[$ids[$i]]['short'] = mb_substr(CMS::call('PARSER')->parseText($reply['text'].'...'), 50);
                            $output[$ids[$i]]['nick']  = $reply['nick'];
                        }
                        if ($content[$ids[$i]]['opened']) {
                            $output[$ids[$i]]['flag'] = 'close';
                            if ($content[$ids[$i]]['comments'] > 10) {
                                $output[$ids[$i]]['flag'] = 'hot';
                            }
                        } else $output[$ids[$i]]['flag'] = 'open';
                    }
                }
                ArraySort($output,'!pinned','!time');

                $TPL = new TEMPLATE(__DIR__.DS.'category.tpl');
                $TPL->set('post_allowed', USER::$logged_in ? TRUE : NULL);
                $TPL->set('topics', $output);

                SYSTEM::defineWindow($category['title'], $TPL->parse());
                if ($count > $perpage) {
                    SYSTEM::defineWindow('', Pagination($count, $perpage, $page, $category['link']));
                }
            }

        } else {
            $section    = CMS::call('FORUM')->getSection($section);
            $categories = CMS::call('FORUM')->getCategories($section['id']);

            SYSTEM::set('pagename', $section['title']);
            if (!empty($section['desc']))
                 SYSTEM::setPageDescription(__('Forum').' - '.$section['title'].' - '.$section['desc']);
            else SYSTEM::setPageDescription(__('Forum').' - '.$section['title']);

            SYSTEM::setPageKeywords($section['id']);

            $output['total_categories'] = sizeof($categories);
            $output['total_topics']  = 0;
            $output['total_replies'] = 0;
            $output['total_views']   = 0;
            $output['link']  = $section['link'];
            $output['title'] = $section['title'];
            if (!empty($categories)) {
                $stat = [];
                $output['categories'] = $categories;
                #
                # Show each category
                #
                foreach ($categories as $key => $category) {
                    $content = CMS::call('FORUM')->getContent($key);
                    $output['categories'][$key]['desc']   = CMS::call('PARSER')->parseText($category['desc']);
                    $output['categories'][$key]['topics'] = sizeof($content);
                    if (!empty($content)) {
                        $output['total_topics'] += $output['categories'][$key]['topics'];
                        foreach ($content as $key => $topic) {
                            $output['total_replies'] += $topic['comments'];
                            $output['total_views']   += $topic['views'];
                        }
                    }
                }
            }
            $TPL = new TEMPLATE(__DIR__.DS.'section.tpl');
            $TPL->set($output);
            SYSTEM::defineWindow('Forum', $TPL->parse());
        }
    } else {
        #
        # Forum main page - Sections and categories
        #
        SYSTEM::set('pagename', __('Forum'));
        SYSTEM::setPageDescription(__('Forum'));

        $output = [];
        $output['total_sections']   = sizeof($sections);
        $output['total_categories'] = 0;
        $output['total_topics']     = 0;
        $output['total_replies']    = 0;
        $output['total_views']      = 0;
        foreach ($sections as $id => $section) {
            $section    = CMS::call('FORUM')->getSection($id);
            $categories = CMS::call('FORUM')->getCategories($id);
            if (!empty($categories)) {
                $output['sections'][$id] = $section;
                $output['total_categories'] += sizeof($categories);
                #
                # Show each category
                #
                foreach ($categories as $key => $category) {
                    $output['sections'][$id]['categories'][$key] = $category;
                    $output['sections'][$id]['categories'][$key]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
                    $content = CMS::call('FORUM')->getContent($key);
                    $output['sections'][$id]['categories'][$key]['topics'] = sizeof($content);
                    if (!empty($content)) {
                        $output['total_topics'] += $output['sections'][$id]['categories'][$key]['topics'];
                        foreach ($content as $key => $topic) {
                            $output['total_replies'] += $topic['comments'];
                            $output['total_views']   += $topic['views'];
                        }
                    }
                }
            }
        }

        if (empty($output)) SYSTEM::showMessage('Database is empty', CreateUrl('index'));

        $TPL = new TEMPLATE(__DIR__.DS.'forum.tpl');
        $TPL->set($output);
        SYSTEM::defineWindow('Forum', $TPL->parse());
    }
} else SYSTEM::showMessage('Database is empty', MODULE.'index');
