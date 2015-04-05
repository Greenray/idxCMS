<?php
# idxCMS Flat Files Content Management Sysytem
# Module Minichat
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$POLLS  = new POLLS();
$polls  = $POLLS->getActivePolls();
$poll   = FILTER::get('REQUEST', 'poll');
$save   = FILTER::get('REQUEST', 'save');
$answer = FILTER::get('REQUEST', 'answer');

if (!empty($poll) && !empty($save)) {
    try {
        $POLLS->voteInPoll($poll, $answer);
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
}

$polls  = $POLLS->getActivePolls();

if (!empty($polls)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'poll.tpl');
    ShowWindow(__('Poll'), $POLLS->showPolls($polls, $TPL));
}
unset($POLLS);
