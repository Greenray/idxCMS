<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Feedbacks management.

if (!defined('idxADMIN') || !USER::$root) die();

$FEEDBACK = new MESSAGE(CONTENT, 'feedback');

if (!empty($REQUEST['submit']) && isset($REQUEST['delete'])) {
    foreach ($REQUEST['delete'] as $key => $id) {
        if (!$FEEDBACK->removeMessage($id)) {
            ShowError('Cannot remove message'.' '.$id);
        }
    }
}

$messages = $FEEDBACK->getMessages();

if (!empty($messages)) {
    $output = [];
    foreach ($messages as $id => $message) {
        $output['messages'][$id] = $message;
        $output['messages'][$id]['id']   = $id;
        $output['messages'][$id]['time'] = '['.FormatTime('d F Y H:i:s', $message['time']).'] ';
        $mail = $REQUEST['mail'];
        $output['messages'][$id]['info'] = __('Message by').' '.CreateUserLink($message['author'], $message['nick']).' ('.$mail.')';
    }
    $TPL = new TEMPLATE(__DIR__.DS.'feedback.tpl');
    $TPL->set($output);
    echo $TPL->parse();
} else {
    ShowMessage('Database is empty', MODULE.'admin');
}
