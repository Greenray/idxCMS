<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POLLS

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
