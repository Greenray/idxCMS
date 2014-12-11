<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE USER - FEEDBACK

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
        ShowWindow('', __('Message sent'), 'center');
        unset($REQUEST);
        unset($FEEDBACK);
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
} elseif (!empty($REQUEST['new_letter'])) {
    if (USER::loggedIn()) {
        if (!empty($REQUEST['subject'])) {
            if (!empty($REQUEST['letter'])) {
                SendMail(
                    CONFIG::getValue('feedback', 'email'),
                    USER::getUser('email'),
                    USER::getUser('nickname').' ('.USER::getUser('username').')',
                    $REQUEST['subject'],
                    $REQUEST['letter']
                );
                ShowWindow('', __('Message sent'));
            } else {
                ShowError(__('Text is empty'));
            }
        } else {
            ShowError(__('Subject is empty'));
        }
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
                            ShowWindow('', __('Message sent'));
                        } else {
                            ShowError(__('Text is empty'));
                        }
                    } else {
                        ShowError(__('Subject is empty'));
                    }
                } else {
                    ShowError(__('Error in email address'));
                }
            } else {
                ShowError(__('What is your name?'));
            }
        } catch (Exception $error) {
            ShowError(__($error->getMessage()));
        }
    }
}

$output = array();

if (!USER::loggedIn()) {
    $output['email']   = empty($REQUEST['email']) ? __('Enter your e-mail') : $REQUEST['email'];
    $output['captcha'] = ShowCaptcha();
}

$output['message'] = $message;
SYSTEM::set('pagename', __('Feedback'));

$TPL = new TEMPLATE(dirname(__FILE__).DS.'feedback.tpl');
ShowWindow(__('Feedback'), $TPL->parse($output));
