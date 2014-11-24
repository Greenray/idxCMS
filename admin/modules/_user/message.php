<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - SEND EMAIL

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

$users = FILTER::get('REQUEST', 'users');

if (FILTER::get('REQUEST', 'pm')) {
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

if (FILTER::get('REQUEST', 'letter')) {
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
            } else {
                ShowMessage('Cannot send email');
            }
        }
    } else {
        ShowMessage('User\'s list is empty');
    }
}

$users  = CMS::call('USER')->getUsersList();
$output = array();

foreach ($users as $user) {
    $output['users'][$user['username']]['name'] = $user['username'];
    $output['users'][$user['username']]['nick'] = $user['nickname'];
}

$output['bbcodes'] = CMS::call('PARSER')->showBbcodesPanel('pm.text');
$TPL = new TEMPLATE(dirname(__FILE__).DS.'message.tpl');
echo $TPL->parse($output);
