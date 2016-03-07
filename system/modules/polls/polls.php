<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module POLLS

if (!defined('idxCMS')) die();

$POLLS  = new POLLS();
$poll   = FILTER::get('REQUEST', 'poll');

if (!empty($poll) && !empty(FILTER::get('REQUEST', 'save'))) {
    try {
        $polls = $POLLS->getActivePolls();
        $POLLS->voteInPoll($poll, FILTER::get('REQUEST', 'answer'));
    } catch (Exception $error) {
        SYSTEM::showError($error->getMessage());
    }
}

$polls = $POLLS->getActivePolls();

if (!empty($polls)) {

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'polls.tpl');
    $TEMPLATE->set('polls', $POLLS->showPolls($polls));
    SYSTEM::defineWindow('Poll', $TEMPLATE->parse());
}
unset($POLLS);
