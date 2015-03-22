<?php
# idxCMS Flat Files Content Management Sysytem
# Module Minichat
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$MC = new MESSAGE(CONTENT, 'minichat');
$messages = $MC->getMessages();

if (!empty($REQUEST['mctext']) && !empty($REQUEST['save'])) {
    try {
        if (USER::loggedIn()) {
            $MC->sendMessage($REQUEST['mctext']);
        }
        FILTER::remove('REQUEST', 'mctext');
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
} else {
    if (!empty($REQUEST['mcaction']) && USER::moderator('minichat')) {
        $id = FILTER::get('REQUEST', 'message');
        switch ($REQUEST['mcaction']) {
            case 'delete':
                if (!empty($messages[$id])) {
                    $MC->removeMessage($id);
                }
                break;

            case 'ban':
                CMS::call('FILTER')->ban();
                break;

            default:
                Redirect('index');
                break;
        }
    }
}

# Show messages
$messages = $MC->getMessages();
$output = [];

if (!empty($messages)) {
    $messages = array_reverse($messages, TRUE);
    $messages = array_slice($messages, 0, (int) CONFIG::getValue('minichat', 'mess-to-show'), TRUE);
    foreach ($messages as $key => $message) {
        $output['msg'][$key] = $message;
        $output['msg'][$key]['id']   = $key;
        $output['msg'][$key]['text'] = CMS::call('PARSER')->parseText($message['text']);
        $output['msg'][$key]['date'] = FormatTime('d.m.Y H:i:s', $message['time']);
        if (USER::moderator('minichat')) {
            $output['msg'][$key]['moderator'] = TRUE;
            $author = USER::getUserData($message['author']);
            if ($author['rights'] === '*') {
                unset($output['msg'][$key]['ip']);
            }
        } else unset($output['msg'][$key]['ip']);
    }

}
unset($MC);

# Show post form
if (USER::loggedIn()) {
    $output['mctext'] = FILTER::get('REQUEST', 'mctext');
    $output['allow_post'] = TRUE;
    if (!CMS::call('USER')->checkRoot()) {
        $output['not_admin'] = TRUE;
        $output['message-length'] = CONFIG::getValue('minichat', 'message-length');
    }
}

if (!empty($output)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'minichat.tpl');
    ShowWindow(__('Minichat'), $TPL->parse($output), 'center');
}
