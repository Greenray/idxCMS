<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Polls.

if (!defined('idxADMIN') || !USER::$root) die();

$POLLS = new POLLS();
$polls = $POLLS->getActivePolls();

try {
    if (!empty($REQUEST['new'])) {
        $POLLS->startPoll($REQUEST['question'], $REQUEST['variants']);
    } elseif (!empty($REQUEST['stop'])) {
        $archived = $POLLS->getArchivedPolls();
        $POLLS->stopPoll($REQUEST['poll']);
    } else {
        if (!empty($REQUEST['delete'])) {
            $POLLS->removePoll($REQUEST['poll']);
        }
    }
} catch (Exception $error) {
    ShowError($error->getMessage());
}

$opened = $POLLS->getActivePolls();
$colors = [
    'red',  'yellow', 'blue', 'green', 'purple', 'aqua',
    'gray', 'olive',  'teal', 'white', 'black'
];
$data   = [];
$output = [];

foreach ($opened as $id => $poll) {
    $data['id'] = $id;
    unset($poll['ips']);
    $data['question'] = $poll['question'];
    $data['answers']  = [];
    foreach ($poll['answers'] as $i => $answer) {
        $data['answers'][$i]['answer'] = $answer;
        $data['answers'][$i]['count']  = $poll['count'][$i];
        $data['answers'][$i]['voices'] = $poll['voices'][$i];
        if ($poll['voices'][$i] === 0)
             $data['answers'][$i]['color'] = 'transparent';
        else $data['answers'][$i]['color'] = $colors[$i];
    }
    $data['total'] = $poll['total'];
    $output['polls'][] = $data;
}

$TPL = new TEMPLATE(__DIR__.DS.'polls.tpl');
$TPL->set($output);
echo $TPL->parse();
