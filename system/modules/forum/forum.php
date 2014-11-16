<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE FORUM

if (!defined('idxCMS')) die();

$sections = CMS::call('FORUM')->getSections();

if ($sections === FALSE) {
    Redirect('forum');
}

if (empty($sections)) {
    ShowWindow(__('Forum'), __('Database is empty'), 'center');
} else {
    $section  = FILTER::get('REQUEST', 'section');
    $category = FILTER::get('REQUEST', 'category');
    $topic    = FILTER::get('REQUEST', 'item');
    if (!empty($topic) && !empty($category) && !empty($section)) {
        $categories = CMS::call('FORUM')->getCategories($section);
        if ($section === FALSE) {
            Redirect('forum');        # Wrong section request
        }
        $content = CMS::call('FORUM')->getContent($category);
        if (($content === FALSE) || empty($content[$topic])) {
            Redirect('forum', $section);        # Wrong category request
        }
        $replies = CMS::call('FORUM')->getComments($topic);
        $reply   = FILTER::get('REQUEST', 'comment');

        if (!empty($REQUEST['save'])) {
            try {
                $result = CMS::call('FORUM')->saveComment($reply, $topic);
            } catch (Exception $error) {
                ShowError(__($error->getMessage()));
            }
        } else {
            if (!empty($REQUEST['action'])) {
                switch ($REQUEST['action']) {
                    case 'edit':
                        if (!empty($content[$topic]['opened'])) {
                            if (empty($reply)) {
                                # Edit topic
                                if (USER::loggedIn()) {
                                    if (!empty($REQUEST['save'])) {
                                        try {
                                            $topic = CMS::call('FORUM')->saveTopic($topic);
                                            Redirect('forum', $section, $category, $topic);
                                        } catch (Exception $error) {
                                            ShowError(__($error->getMessage()));
                                        }
                                    }
                                    $ed = CMS::call('FORUM')->getItem($topic, 'text', FALSE);
                                    $output = array();
                                    $output['topic']  = $ed['id'];
                                    $output['title']  = empty($REQUEST['title'])  ? $ed['title']  : $REQUEST['title'];
                                    $output['text']   = empty($REQUEST['text'])   ? $ed['text']   : $REQUEST['text'];
                                    $output['opened'] = empty($REQUEST['opened']) ? $ed['opened'] : $REQUEST['opened'];
                                    $output['pinned'] = empty($REQUEST['pinned']) ? $ed['pinned'] : $REQUEST['pinned'];
                                    $output['moderator'] = USER::moderator('forum', $topic);
                                    $output['bbCodes']   = ShowBbcodesPanel('topic.text');
                                    $TPL = new TEMPLATE(dirname(__FILE__).DS.'post.tpl');
                                    ShowWindow(__('Edit'), $TPL->parse($output));
                                } else {
                                    ShowError('Your have no right to edit topic');
                                }
                            } else {
                                if (!empty($replies[$reply])) {
                                    if (USER::moderator('forum', $replies[$reply])) {
                                        $output = array();
                                        $output['comment'] = $reply;
                                        $output['text'] = empty($REQUEST['text']) ? $replies[$reply]['text'] : $REQUEST['text'];
                                        if (USER::moderator('forum')) {
                                            $output['moderator'] = TRUE;
                                        }
                                        $output['bbcodes'] = ShowBbcodesPanel('edit.text', !empty($output['moderator']));
                                        $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-edit.tpl');
                                        ShowWindow(__('Edit'), $TPL->parse($output));
                                    }
                                }
                            }
                        } else {
                            ShowError(__('Topic is closed'));
                        }
                        break;
                    case 'delete':
                        try {
                            if (!empty($reply)) {
                                $result = CMS::call('FORUM')->removeComment($reply);
                                $reply  = ($result > $reply) ? $reply : $result;
                            } else {
                                if (USER::moderator('forum', $content[$topic])) {
                                    CMS::call('FORUM')->removeItem($topic);
                                    Redirect('forum', $section, $category);
                                }
                            }
                        } catch (Exception $error) {
                            ShowError($error->getMessage());
                        }
                        break;
                    case 'close':
                        if (USER::moderator('forum')) {
                            CMS::call('FORUM')->setValue($topic, 'opened', FALSE);
                        }
                        break;
                    case 'open':
                        if (USER::moderator('forum')) {
                            CMS::call('FORUM')->setValue($topic, 'opened', TRUE);
                        }
                        break;
                    case 'pin':
                        if (USER::moderator('forum')) {
                            CMS::call('FORUM')->setValue($topic, 'pinned', 1);
//                            CMS::call('FORUM')->sortTopics();
                        }
                        break;
                    case 'unpin':
                        if (USER::moderator('forum')) {
                            CMS::call('FORUM')->setValue($topic, 'pinned', 0);
//                            CMS::call('FORUM')->sortTopics();
                        }
                        break;
                    case 'ban':
                        if (USER::moderator('forum')) {
                            CMS::call('FILTER')->ban();
                        }
                        break;
                    default:
                        Redirect('forum', $section, $category, $topic);
                        break;
                }
            }
        }

        $topic = CMS::call('FORUM')->getItem($topic);
        SYSTEM::set('pagename', $topic['title']);
        SYSTEM::setPageDescription($topic['title']);
        $perpage = (int) CONFIG::getValue('forum', 'replies-per-page');
        if (!empty($reply)) {
            $page = ceil((int)$reply / $perpage);
        } elseif (!empty($result)) {
            $page = ceil((int)$result / $perpage);
        } else {
            $page = (int) FILTER::get('REQUEST', 'page');
        }
        # Don't show topic, if number of comments > per page
        if ($page < 2) {
            # Show topic
            $topic = CMS::call('FORUM')->getItem($topic['id'], 'text');
            $topic['date']   = FormatTime('d F Y H:i:s', $topic['time']);
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
                if (($author['rights'] === '*') || (USER::getUser('username') === $topic['author'])) {
                    unset($topic['ip']);
                }
            } else {
                unset($topic['ip']);
            }
            if (USER::getUser('username') !== 'guest') {
                $topic['profile'] = TRUE;
            }
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'topic.tpl');
            ShowWindow($categories[$category]['title'], $TPL->parse($topic));
            CMS::call('FORUM')->incCount($topic['id'], 'views');
        }
        # Show comments
        $replies = CMS::call('FORUM')->getComments($topic['id']);
        if (!empty($replies)) {
            $count  = sizeof($replies);
            $ids    = array_keys($replies);
            $output = '';
            $pagination = GetPagination($page, $perpage, $count);
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment.tpl');
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $output .= $TPL->parse(CMS::call('FORUM')->getComment($ids[$i], $page));
            }
            ShowWindow(__('Replies'), $output);
            if ($count > $perpage) {
                ShowWindow('', Pagination($count, $perpage, $page, $topic['link']));
            }
        }
        if (USER::loggedIn() && $topic['opened']) {
            # Form to post reply
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-post.tpl');
            ShowWindow(
                __('Reply'),
                $TPL->parse(
                    array(
                        'nickname'  => USER::getUser('nickname'),
                        'not_admin' => !CMS::call('USER')->checkRoot(),
                        'text'      => FILTER::get('REQUEST', 'text'),
                        'action'    => $topic['link'],
                        'bbcodes'   => ShowBbcodesPanel('comment.text'),
                        'comment-length' => CONFIG::getValue('forum', 'reply-length')
                    )
                )
            );
        }
    } elseif (!empty($category) && !empty($section)) {
        # Category request
        $section    = CMS::call('FORUM')->getSection($section);
        $categories = CMS::call('FORUM')->getCategories($section['id']);
        if (empty($categories)) {
            Redirect('forum');
        }
        $category = CMS::call('FORUM')->getCategory($category);
        $content  = CMS::call('FORUM')->getContent($category['id']);
        if ($content === FALSE) {
            Redirect('forum', $section);
        }
        SYSTEM::set('pagename', $category['title']);
        SYSTEM::setPageDescription(__('Forum').' - '.$category['title']);

        if (!empty($REQUEST['new'])) {
            # New topic
            if (USER::loggedIn()) {
                if (!empty($REQUEST['save'])) {
                    try {
                        $result = CMS::call('FORUM')->saveTopic();
                        USER::changeProfileField(USER::getUser('username'), 'topics', '+');
                        Redirect('forum', $section['id'], $category['id']);
                     } catch (Exception $error) {
                        ShowError(__($error->getMessage()));
                     }
                }
                $TPL = new TEMPLATE(dirname(__FILE__).DS.'post.tpl');
                ShowWindow(
                    __('New topic'),
                    $TPL->parse(
                        array(
                            'new'   => TRUE,
                            'title' => FILTER::get('REQUEST', 'title'),
                            'text'  => FILTER::get('REQUEST', 'text'),
                            'moderator' => USER::moderator('forum'),
                            'bbCodes'   => ShowBbcodesPanel('topic.text')
                        )
                    )
                );
            } else {
                ShowError('You have no right to post topic');
            }
        } elseif (empty($content)) {
            $output = '';
            if (USER::loggedIn()) {
                $output['post_allowed'] = TRUE;
            }
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
            ShowWindow($category['title'], $TPL->parse($output));
        } else {
            # List of topics from category
            $ids = array_keys($content);
            $ids = array_reverse($ids);
            $count = sizeof($content);
            $page  = (int) FILTER::get('REQUEST', 'page');
            $perpage = (int) CONFIG::getValue('forum', 'topics-per-page');
            $output  = array();
            $pagination = GetPagination($page, $perpage, $count);
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                if (!empty($content[$ids[$i]])) {
                    $output['topic'][$ids[$i]] = $content[$ids[$i]];
                    $output['topic'][$ids[$i]]['section']  = $section;
                    $output['topic'][$ids[$i]]['category'] = $category;
                    $output['topic'][$ids[$i]]['link'] = $category['link'].ITEM.$ids[$i];
                    $output['topic'][$ids[$i]]['date'] = FormatTime('d m Y', $content[$ids[$i]]['time']);
                    if ($output['topic'][$ids[$i]]['comments'] > 0) {
                        $output['topic'][$ids[$i]]['last_link'] = $output['topic'][$ids[$i]]['link'].COMMENT.$content[$ids[$i]]['comments'];
                    }
                    if ($content[$ids[$i]]['opened']) {
                        $output['topic'][$ids[$i]]['flag'] = 'close';
                        if ($content[$ids[$i]]['comments'] > 10) {
                            $output['topic'][$ids[$i]]['flag'] = 'hot';
                        }
                    } else $output['topic'][$ids[$i]]['flag'] = 'open';
                }
            }
            if (USER::loggedIn()) {
                $output['post_allowed'] = TRUE;
            }
            ArraySort($output['topic'],'!pinned','!time');
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
            ShowWindow($category['title'], $TPL->parse($output));
            if ($count > $perpage) {
                ShowWindow('', Pagination($count, $perpage, $page, $category['link']));
            }
        }
    } elseif (!empty($section)) {
        $section    = CMS::call('FORUM')->getSection($section);
        $categories = CMS::call('FORUM')->getCategories($section['id']);
        if ($categories === FALSE) {
            Redirect('forum');
        }
        SYSTEM::set('pagename', $section['title']);
        if (!empty($section['desc'])) {
            SYSTEM::setPageDescription(__('Forum').' - '.$section['title'].' - '.$section['desc']);
        } else {
            SYSTEM::setPageDescription(__('Forum').' - '.$section['title']);
        }
        SYSTEM::setPageKeywords($section['id']);
        if (empty($categories)) {
            ShowWindow($section['title'], __('Database is empty'), 'center');
        } else {
            $output['total_categories'] = sizeof($categories);
            $output['total_topics']  = 0;
            $output['total_replies'] = 0;
            $output['total_views']   = 0;
            $output['link']  = $section['link'];
            $output['title'] = $section['title'];
            if (!empty($categories)) {
                $stat = array();
                $output['categories'] = $categories;
                # Show each category
                foreach ($categories as $key => $category) {
                    $output['categories'][$key]['desc'] = ParseText($category['desc']);
                    $content = CMS::call('FORUM')->getContent($key);
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
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
            ShowWindow(__('Forum'), $TPL->parse($output));
        }
    } else {
        # Forum main page - Sections and categories
        SYSTEM::set('pagename', __('Forum'));
        SYSTEM::setPageDescription(__('Forum'));
        $output = array();
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
                # Show each category
                foreach ($categories as $key => $category) {
                    $output['sections'][$id]['categories'][$key] = $category;
                    $output['sections'][$id]['categories'][$key]['desc'] = ParseText($category['desc']);
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
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'forum.tpl');
        if (!empty($output)) {
            ShowWindow(__('Forum'), $TPL->parse($output));
        } else {
            ShowWindow(__('Forum'), __('Database is empty'), 'center');
        }
    }
}
?>