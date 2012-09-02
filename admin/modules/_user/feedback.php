<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FEEDBACK

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
    $output = array();
    foreach ($messages as $id => $message) {
        $output['messages'][$id] = $message;
        $output['messages'][$id]['id']   = $id;
        $output['messages'][$id]['time'] = '['.FormatTime('d F Y H:i:s', $message['time']).'] ';
        $mail = FILTER::get('REQUEST', 'mail');
        $output['messages'][$id]['info'] = __('Message by').' '.CreateUserLink($message['author'], $message['nick']).' ('.$mail.')';
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'feedback.tpl');
    echo $TPL->parse($output);
} else ShowMessage(__('Database is empty'));
?>