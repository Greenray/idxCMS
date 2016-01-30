<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module GUESTBOOK

if (!defined('idxCMS')) die();

SYSTEM::set('pagename', __('Guestbook'));
$GB = new MESSAGE(CONTENT, 'guestbook');
$messages = $GB->getMessages();
$id = FILTER::get('REQUEST', 'comment');

if (!empty($REQUEST['save'])) {
    try {
        if (!empty($id) && USER::moderator('guestbook', $messages[$id])) {
            $GB->saveMessage($id, FILTER::get('REQUEST', 'text'));
            unset($id);
        } else {
            if (USER::$logged_in) {
                $GB->sendMessage(FILTER::get('REQUEST', 'text'));
            }
        }
        FILTER::remove('REQUEST', 'text');
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
} else {
    if (!empty($REQUEST['action']) && !empty($id)) {
        switch ($REQUEST['action']) {
            case 'edit':
                if (!empty($messages[$id])) {
                    $TPL = new TEMPLATE(__DIR__.DS.'comment-edit.tpl');
                    if (USER::moderator('guestbook', $messages[$id])) {
                        $TPL->set('comment', $id);
                        $TPL->set('text', empty($REQUEST['text']) ? $messages[$id]['text'] : $REQUEST['text']);
                    }
                    if (USER::moderator('guestbook')) {
                        $TPL->set('moderator', TRUE);
                    }
                    $TPL->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('edit.text', USER::moderator('guestbook')));
                    SYSTEM::defineWindow('Edit', $TPL->parse());
                }
                break;

            case 'delete':
                if (!empty($messages[$id])) {
                    if (USER::moderator('guestbook', $messages[$id])) {
                        $GB->removeMessage($id);
                    }
                }
                break;

            case 'ban':
                if (USER::moderator('guestbook')) CMS::call('FILTER')->ban();
                break;
            default:
                break;
        }
    }
}
#
# Show messages
#
$messages = $GB->getMessages();

if (!empty($messages)) {
    $TPL      = new TEMPLATE(__DIR__.DS.'comment.tpl');
    $output   = '';
    $messages = array_reverse($messages, TRUE);
    $count    = sizeof($messages);
    $ids      = array_keys($messages);
    $perpage  = CONFIG::getValue('guestbook', 'per_page');
    $page     = FILTER::get('REQUEST', 'page');
    $pagination = GetPagination($page, $perpage, $count);
    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
        if (!empty($messages[$ids[$i]])) {
            $messages[$ids[$i]]['id']     = $ids[$i];
            $messages[$ids[$i]]['text']   = CMS::call('PARSER')->parseText($messages[$ids[$i]]['text']);
            $messages[$ids[$i]]['date']   = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
            $messages[$ids[$i]]['avatar'] = GetAvatar($messages[$ids[$i]]['author']);
            $author = USER::getUserData($messages[$ids[$i]]['author']);
            if ($messages[$ids[$i]]['author'] !== $author['user']) {
                $messages[$ids[$i]]['user'] = TRUE;
            }
            $messages[$ids[$i]]['status']  = __($author['status']);
            $messages[$ids[$i]]['stars']   = $author['stars'];
            $messages[$ids[$i]]['country'] = $author['country'];
            $messages[$ids[$i]]['city']    = $author['city'];
            if (($author['rights'] === '*') || (USER::getUser('user') === $messages[$ids[$i]]['author'])) {
                unset($messages[$ids[$i]]['ip']);
            }
            if (USER::moderator('guestbook', $messages[$ids[$i]])) {
                $messages[$ids[$i]]['moderator'] = TRUE;
                $messages[$ids[$i]]['link'] = MODULE.'guestbook';
                if (!empty($messages[$ids[$i]]['ip'])) {
                    if ($page < 2)
                         $messages[$ids[$i]]['ban'] = MODULE.'guestbook';
                    else $messages[$ids[$i]]['ban'] = MODULE.'guestbook'.PAGE.$page;
                }
            }
            $TPL->set($messages[$ids[$i]]);
            $output .= $TPL->parse();
        }
    }

    SYSTEM::defineWindow('Guestbook', $output);
    if ($count > $perpage) {
        SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'guestbook'));
    }
}

unset($GB);
#
# Show post form
#
if (USER::$logged_in) CMS::call('COMMENTS')->showCommentForm(MODULE.'guestbook');
