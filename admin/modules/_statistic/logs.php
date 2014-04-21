<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - LOGS

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['day']) && !empty($REQUEST['viewlog'])) {
    $output = '';
    foreach ($REQUEST['viewlog'] as $logfile) {
        $logfile = basename($logfile);
        if (substr($logfile, -3) == '.gz') {
            $contents = gzfile_get_contents(LOGS.$logfile);
        } else {
            $contents = file_get_contents(LOGS.$logfile);
        }
        $output .= ParseText('[quote='.$logfile.']'.$contents.'[/quote]');
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'log.tpl');
    echo $TPL->parse(array('text' => $output));
} elseif (!empty($REQUEST['archive']) && !empty($REQUEST['viewlog'])) {
    $archive = basename($REQUEST['archive']);
    if (is_readable(LOGS.$archive)) {
        if (!class_exists('tar')) {
            require_once(ADMINLIBS.'tar.php');
        }
        $output = array();
        $TAR = new TAR();
        $TAR->openTAR(LOGS.$archive);
        foreach ($REQUEST['viewlog'] as $logfile) {
            $logfile = basename($logfile);
            if ($gz_contents = $TAR->getFile($logfile)) {
                $gz_contents = $gz_contents['file'];
                if (substr($logfile, -3) == '.gz') {
                    file_put_contents(LOGS.$logfile, $gz_contents, LOCK_EX);
                    $contents = gzfile_get_contents(LOGS.$logfile);
                    unlink(LOGS.$logfile);
                } else {
                    $contents = &$gz_contents;
                }
                $output['text'] .= ParseText('[quote='.$logfile.']'.$contents.'[/quote]');
            }
        }
        unset($TAR);
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'log.tpl');
    echo $TPL->parse($output);
} elseif (!empty($REQUEST['month']) && !empty($REQUEST['browse'])) {
    $browse = basename($REQUEST['browse']);
    if (is_readable(LOGS.$browse)) {
        if (!class_exists('tar')) {
            require_once(ADMINLIBS.'tar.php');
        }
        $TAR = new TAR();
        $TAR->openTAR(LOGS.$browse);
        $output = array();
        foreach ($TAR->files as $key => $file) {
            if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $file['name'], $matches)) {
                $output['day'][$key]['date'] = $matches[1];
                $output['day'][$key]['log']  = $file['name'];
            }
        }
        $output['archive'] = $browse;
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'logs.tpl');
        echo $TPL->parse($output);
        unset($TAR);
    }
} else {
    if (!empty($REQUEST['build'])) {
        if (!class_exists('tar')) {
            require_once(ADMINLIBS.'tar.php');
        }
        CMS::call('LOG')->logMergeByMonth();
        ShowMessage('Done');
    }
    $logs   = GetFilesList(LOGS);
    $output = array();
    foreach ($logs as $key => $log_entry) {
        if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $log_entry, $matches)) {
            $output['day'][$key]['date'] = $matches[1];
            $output['day'][$key]['log']  = $log_entry;
        }
        if (preg_match("/^((.*?)-(.*?))\.tar(|.gz)$/i", $log_entry, $matches)) {
            $output['month'][$key]['date'] = $matches[1];
            $output['month'][$key]['log']  = $log_entry;
        }
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'logs.tpl');
    echo $TPL->parse($output);
}
?>