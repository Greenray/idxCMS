<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
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
        $output['messages'][$id]['text']   = CMS::call('PARSER')->parseText($message['text']);
        $output['messages'][$id]['time'] = '['.FormatTime('d F Y H:i:s', $message['time']).'] ';
        $output['messages'][$id]['info'] = __('Message by').' '.CreateUserLink($message['author'], $message['nick']).' ('.$message['email'].')';
    }
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'feedback.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();
} else {
    ShowMessage('Database is empty', MODULE.'admin');
}
