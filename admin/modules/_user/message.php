<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

$users = $REQUEST['users'];

if ($REQUEST['pm']) {
    if (!empty($users)) {
        foreach ($users as $user) {
            $PM = new MESSAGE(PM_DATA, USER::getUser('username'));
            if ($PM->sendPrivateMessage($user) === FALSE) {
                ShowMessage('Cannot send message'.' for '.$user);
            }
            unset($PM);
        }
    } else ShowMessage('User\'s list is empty');
}

if ($REQUEST['letter']) {
    if (!empty($users)) {
        foreach ($users as $user) {
            $userdata = USER::getUserData($user);
            if (!empty($userdata['email']) && !empty($REQUEST['subj']) && !empty($REQUEST['text'])) {
                SendMail(
                    $userdata['email'],
                    USER::getUser('email'),
                    USER::getUser('nickname'),
                    $REQUEST['subj'],
                    $REQUEST['text']
                );
            } else ShowMessage('Cannot send email');
        }
    } else ShowMessage('User\'s list is empty');
}

$users  = CMS::call('USER')->getUsersList();
$output = [];

foreach ($users as $user) {
    $output['users'][$user['username']]['name'] = $user['username'];
    $output['users'][$user['username']]['nick'] = $user['nickname'];
}

$output['bbcodes'] = CMS::call('PARSER')->showBbcodesPanel('pm.text');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'message.tpl');
echo $TPL->parse($output);
