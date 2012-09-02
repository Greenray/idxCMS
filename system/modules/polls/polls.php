<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE POLLS

if (!defined('idxCMS')) die();

$POLLS  = new POLLS();
$polls  = $POLLS->getActivePolls();
$poll   = FILTER::get('REQUEST', 'poll');
$answer = FILTER::get('REQUEST', 'answer');

if (!empty($poll) && !empty($answer)) {
    try {
        $POLLS->voteInPoll($poll, $answer);
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
}

if (!empty($polls)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'poll.tpl');
    ShowWindow(__('Poll'), $POLLS->showPolls($polls, $TPL));
}
unset($POLLS);
?>