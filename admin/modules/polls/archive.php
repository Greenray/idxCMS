<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Polls
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

$POLLS = new POLLS();
$archived = $POLLS->getArchivedPolls();

if (!empty($REQUEST['remove'])) {
    if (!$POLLS->removePollFromArchive($REQUEST['poll']))
         ShowMessage($POLLS->gerError);
    else ShowMessage(__('Poll removed'));
}

$archived = $POLLS->getArchivedPolls();

if (!empty($archived)) {
    $colors = [
        'red',   'yellow', 'blue', 'green',  'purple', 'aqua',
        'gray', 'olive',  'teal', 'white', 'black'
    ];
    $data   = [];
    $output = '';
    foreach ($archived as $id => $poll) {
        $data['id'] = $id;
        unset($poll['ips']);
        $data['question'] = $poll['question'];
        $data['answers'] = [];
        foreach ($poll['answers'] as $i => $answer) {
            $data['answers'][$i]['id']     = $i;
            $data['answers'][$i]['answer'] = $poll['answers'][$i];
            $data['answers'][$i]['count']  = $poll['count'][$i];
            $data['answers'][$i]['voices'] = $poll['voices'][$i];
            if ($poll['voices'][$i] === 0)
                 $data['answers'][$i]['color'] = 'transparent';
            else $data['answers'][$i]['color'] = $colors[$i-1];
        }
        $data['total']     = $poll['total'];
        $output['polls'][] = $data;
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'archive.tpl');
    echo($TPL->parse($output));
} else {
    ShowMessage(__('Database is empty'));
}
