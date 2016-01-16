<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module USER: Private messages

if (!defined('idxCMS')) die();

SYSTEM::set('pagename', __('Private messages'));

if (!USER::$logged_in) {
    SYSTEM::showMessage('You are not logged in!');
} elseif (!empty($REQUEST['save'])) {
    #
    # Send message
    #
    try {
        $PM = new MESSAGE(PM_DATA, USER::getUser('user'));
        $PM->sendPrivateMessage($REQUEST['for'], $REQUEST['text']);
        SYSTEM::ShowMessage('Message sent');
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
    unset($PM);

} elseif (!empty($REQUEST['for'])) {
    #
    # Post new message
    #
    if ($REQUEST['for'] === USER::getUser('user')) {
        SYSTEM::showMessage('You cannot send message to yourself');
    } else {
        $user = USER::getUserData($REQUEST['for']);
        $TPL  = new TEMPLATE(__DIR__.DS.'comment-post.tpl');
        $TPL->set('action',         '');
        $TPL->set('nick',           $user['nick']);
        $TPL->set('text',           FILTER::get('REQUEST', 'text'));
        $TPL->set('message_length', USER::$root ? '' : CONFIG::getValue('pm', 'message_length'));
        $TPL->set('bbcodes',        CMS::call('PARSER')->showBbcodesPanel('comment.text'));
        $TPL->set('for',            $REQUEST['for']);
        SYSTEM::defineWindow('Private message', $TPL->parse());
    }
} else {
    $PM = new MESSAGE(PM_DATA, USER::getUser('user'));
    try {
        if (!empty($REQUEST['delete'])) $PM->removeMessage((int) $REQUEST['delete'], 'inbox');
        if (!empty($REQUEST['remove'])) $PM->removeMessage((int) $REQUEST['remove'], 'outbox');
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
    if (!empty($REQUEST['mode'])) {
        if ($REQUEST['mode'] === 'outbox') {
            $messages = $PM->getMessages('outbox');
            if (!empty($messages)) {
                $TPL = new TEMPLATE(__DIR__.DS.'pm.tpl');
                $output  = '';
                $count   = sizeof($messages);
                $ids     = array_keys($messages);
                $page    = (int) FILTER::get('REQUEST', 'page');
                $perpage = (int) CONFIG::getValue('pm', 'per_page');
                $pagination = GetPagination($page, $perpage, $count);
                for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                    if (!empty($messages[$ids[$i]])) {
                        $messages[$ids[$i]]['id']   = $ids[$i];
                        $messages[$ids[$i]]['text'] = CMS::call('PARSER')->parseText($messages[$ids[$i]]['text']);
                        $messages[$ids[$i]]['time'] = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
                        $user = USER::getUserData($messages[$ids[$i]]['to']);
                        $messages[$ids[$i]]['nick']    = $user['nick'];
                        $messages[$ids[$i]]['avatar']  = GetAvatar($messages[$ids[$i]]['to']);
                        $messages[$ids[$i]]['country'] = $user['country'];
                        $messages[$ids[$i]]['city']    = $user['city'];
                        $TPL->set($messages[$ids[$i]]);
                        $output .= $TPL->parse();
                    }
                }
                SYSTEM::defineWindow('Messages', $output);
                if ($count > $perpage) {
                    SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&user='.$REQUEST['user'].'&mode=outbox'));
                }
            } else SYSTEM::showMessage('Database is empty');
        } elseif ($REQUEST['mode'] === 'inbox') {
            $messages = $PM->getMessages('inbox');
            if (!empty($messages)) {
                if (!empty($REQUEST['reply'])) {
                    $user = USER::getUserData($REQUEST['reply']);
                    $TPL  = new TEMPLATE(__DIR__.DS.'comment-post.tpl');
                    $TPL->set('action',         '');
                    $TPL->set('nick',           $user['nick']);
                    $TPL->set('text',           empty($REQUEST['text']) ? '[quote]'.$messages[$REQUEST['re']]['text'].'[/quote]' : $REQUEST['text']);
                    $TPL->set('message_length', USER::$root ? '' : CONFIG::getValue('pm', 'message_length'));
                    $TPL->set('bbcodes',        CMS::call('PARSER')->showBbcodesPanel('comment.text'));
                    $TPL->set('for',            $REQUEST['reply']);
                    SYSTEM::defineWindow('Reply', $TPL->parse());
                } else {
                    $PM->setAllNoNew();
                    $TPL = new TEMPLATE(__DIR__.DS.'pm.tpl');
                    $output  = '';
                    $count   = sizeof($messages);
                    $ids     = array_keys($messages);
                    $ids     = array_reverse($ids);
                    $page    = (int) FILTER::get('REQUEST', 'page');
                    $perpage = (int) CONFIG::getValue('pm', 'per_page');
                    $pagination = GetPagination($page, $perpage, $count);
                    for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                        if (!empty($messages[$ids[$i]])) {
                            $messages[$ids[$i]]['inbox'] = TRUE;
                            $messages[$ids[$i]]['id']    = $ids[$i];
                            $messages[$ids[$i]]['text']  = CMS::call('PARSER')->parseText($messages[$ids[$i]]['text']);
                            $messages[$ids[$i]]['time']  = FormatTime('d F Y H:i:s', $messages[$ids[$i]]['time']);
                            if ($messages[$ids[$i]]['author'] !== 'IDX') {
                                $user = USER::getUserData($messages[$ids[$i]]['author']);
                                $messages[$ids[$i]]['avatar']  = GetAvatar($user['user']);
                                $messages[$ids[$i]]['status']  = __($user['status']);
                                $messages[$ids[$i]]['stars']   = $user['stars'];
                                $messages[$ids[$i]]['country'] = $user['country'];
                                $messages[$ids[$i]]['city']    = $user['city'];
                                $messages[$ids[$i]]['reply']   = TRUE;
                            }
                            $TPL->set($messages[$ids[$i]]);
                            $output .= $TPL->parse();
                        }
                    }
                    SYSTEM::defineWindow('Messages', $output);
                    if ($count > $perpage) {
                        SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&mode=inbox'));
                    }
                }
            } else {
                SYSTEM::showMessage('Database is empty');
            }
        }
    } else {
        SYSTEM::defineWindow(
            'Messages',
            '<div class="center">
                <a href="'.MODULE.'user.pm&mode=inbox" title="{mess_info}">'.__('Inbox').'</a>
                <a href="'.MODULE.'user.pm&mode=outbox" title="{mess_info}">'.__('Outbox').'</a>
             </div>'
        );
    }
    unset($PM);
}
