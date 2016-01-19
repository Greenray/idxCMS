<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Search keywords managment.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['clean'])) {
    file_put_contents(CONTENT.'searchwords', '');
    ShowMessage('Done');
} else {
    $keywords = file(CONTENT.'searchwords', FILE_IGNORE_NEW_LINES);

    if (!empty($keywords)) {
        $output = [];
        $exists = [];
        $i = 0;
        $word[$i] = $keywords[0];
        $output['words'][$i] = explode('|', $keywords[0]);
        $output['words'][$i]['count'] = 1;
        unset($keywords[0]);
        foreach ($keywords as $values) {
            if (!in_array($values, $word)) {
                ++$i;
                $word[$i] = $values;
                $output['words'][$i] = explode('|', $values);
                $output['words'][$i]['count'] = 1;
            } else {
                foreach ($word as $key => $params) {
                    if ($params === $values) {
                        $output['words'][$key]['count'] += 1;
                    }
                }
            }
        }
        $TPL = new TEMPLATE(__DIR__.DS.'searchwords.tpl');
        $TPL->set($output);
        echo $TPL->parse();
    } else ShowMessage('No data', MODULE.'admin');
}
