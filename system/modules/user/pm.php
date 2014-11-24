<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE USER - PRIVATE MESSAGES

if (!defined('idxCMS')) die();

SYSTEM::set('pagename', __('Private messages'));

if (!USER::loggedIn()) {
    ShowError(__('You are not logged in!'));
} elseif (!empty($REQUEST['save'])) {
    # Send message
    $PM = new MESSAGE(PM_DATA, USER::getUser('username'));
    if ($PM->sendPrivateMessage($REQUEST['for']) !== FALSE) {
        ShowWindow(__('Private messages'), __('Message sent'), 'center');
    } else {
        ShowError('Cannot send message');
    }
    unset($PM);
} elseif (!empty($REQUEST['for'])) {
    # Post new message
    if ($REQUEST['for'] === USER::getUser('username')) {
        ShowError(__('You cannot send message to yourself'));
    } else {
        $user = USER::getUserData($REQUEST['for']);
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-post.tpl');
        ShowWindow(
            __('Private message'),
            $TPL->parse(
                array(
                    'action'         => '',
                    'nickname'       => $user['nickname'],
                    'text'           => FILTER::get('REQUEST', 'text'),
                    'comment-length' => CMS::call('USER')->checkRoot() ? '' : CONFIG::getValue('pm', 'message-length'),
                    'bbcodes'        => CMS::call('PARSER')->showBbcodesPanel('comment.text'),
                    'for'            => $REQUEST['for']
                )
            )
        );
    }
} else {
    $PM = new MESSAGE(PM_DATA, USER::getUser('username'));
    if (!empty($REQUEST['delete'])) $PM->removeMessage((int) $REQUEST['delete'], 'inbox');
    if (!empty($REQUEST['remove'])) $PM->removeMessage((int) $REQUEST['remove'], 'outbox');
    if (!empty($REQUEST['mode'])) {
        if ($REQUEST['mode'] === 'outbox') {
            $messages = $PM->getMessages('outbox');
            if (!empty($messages)) {
                $count   = sizeof($messages);
                $ids     = array_keys($messages);
                $page    = (int) FILTER::get('REQUEST', 'page');
                $perpage = (int) CONFIG::getValue('pm', 'per-page');
                $pagination = GetPagination($page, $perpage, $count);
                $TPL = new TEMPLATE(dirname(__FILE__).DS.'pm.tpl');
                $output = '';
                for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                    if (!empty($messages[$ids[$i]])) {
                        $messages[$ids[$i]]['id']   = $ids[$i];
                        $messages[$ids[$i]]['text'] = CMS::call('PARSER')->parseText($messages[$ids[$i]]['text']);
                        $messages[$ids[$i]]['time'] = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
                        $user = USER::getUserData($messages[$ids[$i]]['to']);
                        $messages[$ids[$i]]['nick'] = $user['nickname'];
                        $messages[$ids[$i]]['avatar'] = GetAvatar($messages[$ids[$i]]['to']);
                        $messages[$ids[$i]]['country'] = $user['country'];
                        $messages[$ids[$i]]['city']    = $user['city'];
                        $output .= $TPL->parse($messages[$ids[$i]]);
                    }
                }
                ShowWindow(__('Messages'), $output);
                if ($count > $perpage) {
                    ShowWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&user='.$REQUEST['user'].'&mode=outbox'));
                }
            } else ShowWindow(__('Messages'), __('Database is empty'), 'center');
        } elseif ($REQUEST['mode'] === 'inbox') {
            $messages = $PM->getMessages('inbox');
            if (!empty($messages)) {
                if (!empty($REQUEST['reply'])) {
                    $user = USER::getUserData($REQUEST['reply']);
                    $TPL = new TEMPLATE(dirname(__FILE__).DS.'comment-post.tpl');
                    ShowWindow(
                        __('Reply'),
                        $TPL->parse(
                            array(
                                'action'         => '',
                                'nickname'       => $user['nickname'],
                                'text'           => empty($REQUEST['text']) ? '[quote]'.$messages[$REQUEST['re']]['text'].'[/quote]' : $REQUEST['text'],
                                'comment-length' => CMS::call('USER')->checkRoot() ? '' : CONFIG::getValue('pm', 'message-length'),
                                'bbcodes'        => CMS::call('PARSER')->showBbcodesPanel('comment.text'),
                                'for'            => $REQUEST['reply']
                            )
                        )
                    );
                } else {
                    $PM->setAllNoNew();
                    $count   = sizeof($messages);
                    $ids     = array_keys($messages);
                    $ids     = array_reverse($ids);
                    $page    = (int) FILTER::get('REQUEST', 'page');
                    $perpage = (int) CONFIG::getValue('pm', 'per-page');
                    $pagination = GetPagination($page, $perpage, $count);
                    $TPL = new TEMPLATE(dirname(__FILE__).DS.'pm.tpl');
                    $output = '';
                    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                        if (!empty($messages[$ids[$i]])) {
                            $messages[$ids[$i]]['inbox'] = TRUE;
                            $messages[$ids[$i]]['id']    = $ids[$i];
                            $messages[$ids[$i]]['text']  = CMS::call('PARSER')->parseText($messages[$ids[$i]]['text']);
                            $messages[$ids[$i]]['time']  = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
                            if ($messages[$ids[$i]]['author'] !== 'IDX') {
                                $user = USER::getUserData($messages[$ids[$i]]['author']);
                                $messages[$ids[$i]]['avatar']  = GetAvatar($user['username']);
                                $messages[$ids[$i]]['status']  = __($user['status']);
                                $messages[$ids[$i]]['stars']   = $user['stars'];
                                $messages[$ids[$i]]['country'] = $user['country'];
                                $messages[$ids[$i]]['city']    = $user['city'];
                                $messages[$ids[$i]]['reply']   = TRUE;
                            }
                            $output .= $TPL->parse($messages[$ids[$i]]);
                        }
                    }
                    ShowWindow(__('Messages'), $output);
                    if ($count > $perpage) {
                        ShowWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&amp;mode=inbox'));
                    }
                }
            } else {
                ShowWindow(__('Messages'), __('Database is empty'), 'center');
            }
        }
    } else {
        ShowWindow(
            __('Messages'),
            '<div class="center">
                <a href="'.MODULE.'user.pm&amp;mode=inbox" title="{mess_info}">'.__('Inbox').'</a>
                <a href="'.MODULE.'user.pm&amp;mode=outbox" title="{mess_info}">'.__('Outbox').'</a>
             </div>',
            __('Database is empty'),
            'center'
        );
    }
    unset($PM);
}
