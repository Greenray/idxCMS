<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module MINICHAT

if (!defined('idxCMS')) die();

$MC = new MESSAGE(CONTENT, 'minichat');
$messages = $MC->getMessages();

if (!empty($REQUEST['mctext']) && !empty($REQUEST['save'])) {
    try {
        if (USER::$logged_in) {
            $MC->sendMessage($REQUEST['mctext']);
        }
        FILTER::remove('REQUEST', 'mctext');
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
}
if (USER::moderator('minichat')) {
    if (!empty($REQUEST['delete'])) {
        $id = FILTER::get('REQUEST', 'message');
        if (!empty($messages[$id])) {
            $MC->removeMessage($id);
        }
    }
    if (!empty($REQUEST['ban'])) CMS::call('FILTER')->ban();
}
#
# Show messages
#
$messages = $MC->getMessages();
$output = [];

if (!empty($messages)) {
    $messages = array_reverse($messages, TRUE);

//    $messages = array_slice($messages, 0, CONFIG::getValue('minichat', 'mess-to-show'), TRUE);

    foreach ($messages as $key => $message) {
        $output['messages'][$key]         = $message;
        $output['messages'][$key]['id']   = $key;
        $output['messages'][$key]['text'] = CMS::call('PARSER')->parseText($message['text']);
        $output['messages'][$key]['date'] = FormatTime('d.m.Y H:i:s', $message['time']);
        if (USER::moderator('minichat')) {
            $output['messages'][$key]['moderator'] = TRUE;
            $author = USER::getUserData($message['author']);
            if ($author['rights'] === '*') {
                unset($output['messages'][$key]['ip']);
            }
        } else unset($output['messages'][$key]['ip']);
    }
}
unset($MC);
#
# Show post form
#
if (USER::$logged_in) {
    $output['mctext']     = FILTER::get('REQUEST', 'mctext');
    $output['allow_post'] = TRUE;
    if (!USER::$root) {
        $output['message_length'] = CONFIG::getValue('minichat', 'message_length');
    }
}

$TPL = new TEMPLATE(__DIR__.DS.'minichat.tpl');
$TPL->set($output);
SYSTEM::defineWindow('Minichat', $TPL->parse());
