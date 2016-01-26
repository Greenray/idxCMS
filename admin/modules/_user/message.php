<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Messages.

if (!defined('idxADMIN') || !USER::$root) die();

$users = $REQUEST['users'];

if ($REQUEST['pm']) {
    if (!empty($users)) {
        foreach ($users as $user) {
            $PM = new MESSAGE(PM_DATA, USER::getUser('user'));
            if (!$PM->sendPrivateMessage($user, $REQUEST['text'])) {
                ShowError('Cannot send message'.' for '.$user);
            }
            unset($PM);
        }
    } else ShowError('User\'s list is empty');
}

if ($REQUEST['letter']) {
    if (!empty($users)) {
        foreach ($users as $user) {
            $userdata = USER::getUserData($user);
            if (!empty($userdata['email']) && !empty($REQUEST['subj']) && !empty($REQUEST['text'])) {
                SendMail(
                    $userdata['email'],
                    USER::getUser('email'),
                    USER::getUser('nick'),
                    $REQUEST['subj'],
                    $REQUEST['text']
                );
            } else ShowError('Cannot send email');
        }
    } else ShowMessage('User\'s list is empty');
}

$users  = CMS::call('USER')->getUsersList();
$output = [];

foreach ($users as $user) {
    $output['users'][$user['user']]['name'] = $user['user'];
    $output['users'][$user['user']]['nick'] = $user['nick'];
}
unset ($output['users']['admin']);

$output['bbcodes'] = CMS::call('PARSER')->showBbcodesPanel('pm.text');

$TPL = new TEMPLATE(__DIR__.DS.'message.tpl');
$TPL->set($output);
echo $TPL->parse();
