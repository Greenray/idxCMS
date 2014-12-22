<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - KEYWORDS

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

$keywords = [];

if (!empty($REQUEST['clean'])) {
    file_put_contents(CONTENT.'keywords', '');
    ShowMessage('Done');
} elseif (file_exists(CONTENT.'keywords')) {
    $keywords = file(CONTENT.'keywords', FILE_IGNORE_NEW_LINES);
    $output = [];
    $exists = [];
    if (!empty($keywords)) {
        $i = 0;
        $word[$i] = $keywords[0];
        $output['word'][$i] = explode('|', $keywords[0]);
        $output['word'][$i]['count'] = 1;
        unset($keywords[0]);
        foreach ($keywords as $values) {
            if (!in_array($values, $word)) {
                ++$i;
                $word[$i] = $values;
                $output['word'][$i] = explode('|', $values);
                $output['word'][$i]['count'] = 1;
            } else {
                foreach ($word as $key => $params) {
                    if ($params === $values) {
                        $output['word'][$key]['count'] += 1;
                    }
                }
            }
        }
    }
}

if (!empty($output)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'keywords.tpl');
    echo $TPL->parse($output);
}
?>