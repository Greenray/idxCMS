<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module POLLS

if (!defined('idxCMS')) die();

$POLLS  = new POLLS();
$poll   = FILTER::get('REQUEST', 'poll');
$save   = FILTER::get('REQUEST', 'save');
$answer = FILTER::get('REQUEST', 'answer');

if (!empty($poll) && !empty($save)) {
    try {
        $polls = $POLLS->getActivePolls();
        $POLLS->voteInPoll($poll, $answer);
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
}

$polls = $POLLS->getActivePolls();

if (!empty($polls)) {

    $TPL = new TEMPLATE(__DIR__.DS.'polls.tpl');
    $TPL->set('polls', $POLLS->showPolls($polls));
    SYSTEM::defineWindow('Poll', $TPL->parse());
}
unset($POLLS);
