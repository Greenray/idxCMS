<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Feedback

if (!defined('idxCMS')) die();

$message = FILTER::get('REQUEST', 'text');
#
# Here the desired filtering, but now we have another problem.
# Filter, you know how.
#
if (!empty($message)) {
    try {
        CheckCaptcha();
        $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
        $FEEDBACK->sendFeedback(
            $message,
            empty($REQUEST['email']) ? USER::getUser('email') : $REQUEST['email'],
            empty($REQUEST['name'])  ? USER::getUser('name')  : $REQUEST['name']
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
                SYSTEM::showMessage('', __('Message sent'));

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

$TEMPLATE = new TEMPLATE(__DIR__.DS.'feedback.tpl');

if (!USER::$logged_in) {
    $TEMPLATE->set('email',   empty($REQUEST['email']) ? '' : $REQUEST['email']);
    $TEMPLATE->set('captcha', ShowCaptcha());
}
$TEMPLATE->set('logged_in', USER::$logged_in);
$TEMPLATE->set('message', $message);
$TEMPLATE->set('message_length', USER::$root ? NULL : CONFIG::getValue('feedback', 'message_length'));
$TEMPLATE->set('bbcodes', CMS::call('PARSER')->showBbcodesPanel('feedback.text'));

SYSTEM::set('pagename', __('Feedback'));
SYSTEM::defineWindow('Feedback', $TEMPLATE->parse());
