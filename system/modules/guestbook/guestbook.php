<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE GUESTBOOK

if (!defined('idxCMS')) die();

SYSTEM::set('pagename', __('Guestbook'));
$GB = new MESSAGE(CONTENT, 'guestbook');
$messages = $GB->getMessages();
$id = FILTER::get('REQUEST', 'message');

if (!empty($REQUEST['save'])) {
    try {
        if (!empty($id) && USER::moderator('guestbook', $messages[$id])) {
            $GB->saveMessage($id, FILTER::get('REQUEST', 'text'));
            unset($id);
        } else {
            if (USER::loggedIn() || CONFIG::getValue('guestbook', 'allow-guests-post')) {
                CheckCaptcha();
                $GB->sendMessage(FILTER::get('REQUEST', 'text'));
            }
        }
        FILTER::remove('REQUEST', 'text');
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
} else {
    if (!empty($REQUEST['action']) && !empty($id)) {
        switch ($REQUEST['action']) {
            case 'edit':
                if (!empty($messages[$id])) {
                    $output = array();
                    if (USER::moderator('guestbook', $messages[$id])) {
                        $output['comment'] = $id;
                        $output['text'] = empty($REQUEST['text']) ? $messages[$id]['text'] : $REQUEST['text'];
                    }
                    if (USER::moderator('guestbook')) {
                        $output['moderator'] = TRUE;
                    }
                    $output['bbcodes'] = ShowBbcodesPanel('edit.text', !empty($output['moderator']));
                    $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-edit.tpl');
                    ShowWindow(__('Edit'), $TPL->parse($output));
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
                if (USER::moderator('guestbook'))
                    CMS::call('FILTER')->ban();
                break;
            default:
                Redirect('guestbook');
                break;
        }
    }
}

# Show messages
$messages = $GB->getMessages();
if (!empty($messages)) {
    $messages = array_reverse($messages, TRUE);
    $count    = sizeof($messages);
    $ids      = array_keys($messages);
    $perpage  = (int) CONFIG::getValue('guestbook', 'per-page');
    $page     = (int) FILTER::get('REQUEST', 'page');
    $pagination = GetPagination($page, $perpage, $count);
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'guestbook.tpl');
    $output = '';
    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
        if (!empty($messages[$ids[$i]])) {
            $messages[$ids[$i]]['id']   = $ids[$i];
            $messages[$ids[$i]]['text'] = ParseText($messages[$ids[$i]]['text']);
            $messages[$ids[$i]]['date'] = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
            $messages[$ids[$i]]['avatar'] = GetAvatar($messages[$ids[$i]]['author']);
            if ($messages[$ids[$i]]['author'] !== 'guest') {
                if ($messages[$ids[$i]]['author'] !== USER::getUser('username')) {
                    $messages[$ids[$i]]['user'] = TRUE;
                }
                $author = USER::getUserData($messages[$ids[$i]]['author']);
                $messages[$ids[$i]]['status']  = __($author['status']);
                $messages[$ids[$i]]['stars']   = $author['stars'];
                $messages[$ids[$i]]['country'] = $author['country'];
                $messages[$ids[$i]]['city']    = $author['city'];
                if (($author['rights'] === '*') || (USER::getUser('username') === $messages[$ids[$i]]['author'])) {
                    unset($messages[$ids[$i]]['ip']);
                }
            }
            if (USER::moderator('guestbook', $messages[$ids[$i]])) {
                $messages[$ids[$i]]['moderator'] = TRUE;
                if (!empty($messages[$ids[$i]]['ip'])) {
                    if ($page < 2)
                         $messages[$ids[$i]]['ban'] = MODULE.'guestbook';
                    else $messages[$ids[$i]]['ban'] = MODULE.'guestbook'.PAGE.$page;
                }
            }
            $output .= $TPL->parse($messages[$ids[$i]]);
        }
    }
    ShowWindow(__('Guestbook'), $output);
    if ($count > $perpage) {
        ShowWindow('', Pagination($count, $perpage, $page, MODULE.'guestbook'));
    }
} else ShowWindow(__('Guestbook'), __('Database is empty'), 'center');
unset($GB);

# Show post form
if (USER::loggedIn() || CONFIG::getValue('guestbook', 'allow-guests-post')) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-post.tpl');
    ShowWindow(
        __('Message'),
        $TPL->parse(
            array(
                'nickname'       => USER::getUser('nickname'),
                'not_admin'      => !CMS::call('USER')->checkRoot(),
                'text'           => FILTER::get('REQUEST', 'text'),
                'action'         => MODULE.'guestbook',
                'comment-length' => CONFIG::getValue('guestbook', 'message-length'),
                'bbcodes'        => ShowBbcodesPanel('post-comment.text'),
                'captcha'        => ShowCaptcha()
            )
        )
    );
}
?>