<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Private messages

if (!defined('idxCMS')) die();

SYSTEM::set('pagename', __('Private messages'));

if (!USER::$logged_in) {
    SYSTEM::showError('You are not logged in!', MODULE.'index');
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
        SYSTEM::showError('You cannot send message to yourself', MODULE.'user.pm');
    } else {
        CMS::call('COMMENTS')->showCommentForm(MODULE.'pm&', $REQUEST['for']);
    }

} else {
    $PM = new MESSAGE(PM_DATA, USER::getUser('user'));
    try {
        if (!empty($REQUEST['delete'])) $PM->removeMessage($REQUEST['delete'], 'inbox');
        if (!empty($REQUEST['remove'])) $PM->removeMessage($REQUEST['remove'], 'outbox');

    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }

    if (!empty($REQUEST['mode'])) {
        if ($REQUEST['mode'] === 'outbox') {
            $messages = $PM->getMessages('outbox');
            if (!empty($messages)) {
                $TEMPLATE = new TEMPLATE(__DIR__.DS.'pm.tpl');
                $output  = '';
                $count   = sizeof($messages);
                $ids     = array_keys($messages);
                $page    = FILTER::get('REQUEST', 'page');
                $perpage = CONFIG::getValue('pm', 'per_page');
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
                        $TEMPLATE->set($messages[$ids[$i]]);
                        $output .= $TEMPLATE->parse();
                    }
                }

                SYSTEM::defineWindow('Messages', $output);

                if ($count > $perpage) {
                    SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&user='.$REQUEST['user'].'&mode=outbox'));
                }

            } else  SYSTEM::showMessage('Box is empty', MODULE.'user.pm');

        } elseif ($REQUEST['mode'] === 'inbox') {
            $messages = $PM->getMessages('inbox');
            if (!empty($messages)) {
                if (!empty($REQUEST['reply'])) {
                    $user = USER::getUserData($REQUEST['reply']);
                    $TEMPLATE = new TEMPLATE(__DIR__.DS.'comment-post.tpl');
                    $TEMPLATE->set('action',         '');
                    $TEMPLATE->set('nick',           $user['nick']);
                    $TEMPLATE->set('text',           empty($REQUEST['text']) ? '[quote]'.$messages[$REQUEST['re']]['text'].'[/quote]' : $REQUEST['text']);
                    $TEMPLATE->set('bbcodes',        CMS::call('PARSER')->showBbcodesPanel('comment.text'));
                    $TEMPLATE->set('message_length', USER::$root ? '' : CONFIG::getValue('pm', 'message_length'));
                    $TEMPLATE->set('for',            $REQUEST['reply']);
                    SYSTEM::defineWindow('Reply', $TEMPLATE->parse());
                } else {
                    $PM->setAllNoNew();
                    $TEMPLATE = new TEMPLATE(__DIR__.DS.'pm.tpl');
                    $output  = '';
                    $count   = sizeof($messages);
                    $ids     = array_keys($messages);
                    $ids     = array_reverse($ids);
                    $page    = FILTER::get('REQUEST', 'page');
                    $perpage = CONFIG::getValue('pm', 'per_page');
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

                            $TEMPLATE->set($messages[$ids[$i]]);
                            $output .= $TEMPLATE->parse();
                        }
                    }
                    SYSTEM::defineWindow('Messages', $output);

                    if ($count > $perpage) {
                        SYSTEM::defineWindow('', Pagination($count, $perpage, $page, MODULE.'user.pm&mode=inbox'));
                    }
                }

            } else SYSTEM::showMessage('Box is empty', MODULE.'user.pm');
        }

    } else {
        SYSTEM::defineWindow(
            'Messages',
            '<div class="center">
                <a href="'.MODULE.'user.pm&mode=inbox">'.__('Inbox').'</a>
                <a href="'.MODULE.'user.pm&mode=outbox">'.__('Outbox').'</a>
             </div>'
        );
    }
    unset($PM);
}
