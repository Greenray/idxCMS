<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Feedback

if (!defined('idxCMS')) die();

$message = FILTER::get('REQUEST', 'message');

if (!empty($message)) {
    try {
        CheckCaptcha();
        $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
        $FEEDBACK->sendFeedback(
            $message,
            '',
            empty($REQUEST['email']) ? USER::getUser('email') : $REQUEST['email']
        );
        SYSTEM::defineWindow('', __('Message sent'));
        unset($REQUEST);
        unset($FEEDBACK);
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
} elseif (!empty($REQUEST['new_letter'])) {
    if (USER::$logged_in) {
        if (!empty($REQUEST['subject'])) {
            if (!empty($REQUEST['letter'])) {
                SendMail(
                    CONFIG::getValue('feedback', 'email'),
                    USER::getUser('email'),
                    USER::getUser('nick').' ('.USER::getUser('user').')',
                    $REQUEST['subject'],
                    $REQUEST['letter']
                );
                SYSTEM::defineWindow('', __('Message sent'));
            } else SYSTEM::showError('Text is empty');
        } else SYSTEM::showError('Subject is empty');

    } else {
        try {
            CheckCaptcha();
            if (!empty($REQUEST['sender_name'])) {
                if (!empty($REQUEST['sender-email']) && CMS::call('FILTER')->validEmail($REQUEST['sender-email'])) {
                    if (!empty($REQUEST['subject'])) {
                        if (!empty($REQUEST['letter'])) {
                            SendMail(
                                CONFIG::getValue('feedback', 'email'),
                                $REQUEST['sender-email'],
                                $REQUEST['sender_name'],
                                $REQUEST['subject'],
                                $REQUEST['letter']
                            );
                            SYSTEM::defineWindow('', __('Message sent'));
                        } else SYSTEM::showError('Text is empty');
                    } else SYSTEM::showError('Subject is empty');
                } else SYSTEM::showError('Error in email address');
            } else SYSTEM::showError('What is your name?');

        } catch (Exception $error) {
            SYSTEM::showError($error->getMessage());
        }
    }
}

$TPL = new TEMPLATE(__DIR__.DS.'comment-post.tpl');

if (!USER::$logged_in) {
    $TPL->set('email',   empty($REQUEST['email']) ? __('Enter your e-mail') : $REQUEST['email']);
    $TPL->set('captcha', ShowCaptcha());
}

$TPL->set('text', $message);
$TPL->set('message_length', USER::$root ? NULL : CONFIG::getValue('feedback', 'message_length'));
$TPL->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('feedback.text'));
SYSTEM::set('pagename', __('Feedback'));
SYSTEM::defineWindow('Feedback', $TPL->parse());
