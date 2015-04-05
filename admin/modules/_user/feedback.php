<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

$FEEDBACK = new MESSAGE(CONTENT, 'feedback');

if (!empty($REQUEST['submit']) && isset($REQUEST['delete'])) {
    foreach ($REQUEST['delete'] as $key => $id) {
        if (!$FEEDBACK->removeMessage($id)) {
            ShowMessage(__('Cannot remove message').' '.$id);
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
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'feedback.tpl');
    echo $TPL->parse($output);
} else {
    ShowMessage(__('Database is empty'));
}
