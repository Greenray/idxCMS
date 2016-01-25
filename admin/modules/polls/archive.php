<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Polls archive.

if (!defined('idxADMIN') || !USER::$root) die();

$POLLS = new POLLS();
$archived = $POLLS->getArchivedPolls();

if (!empty($REQUEST['remove'])) {
    if (!$POLLS->removePollFromArchive($REQUEST['poll']))
         ShowError($POLLS->gerError);
    else ShowMessage('Poll removed');
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

    $TPL = new TEMPLATE(__DIR__.DS.'archive.tpl');
    $TPL->set($output);
    echo($TPL->parse());

} else {
    ShowMessage('Database is empty', MODULE.'admin');
}
