<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Logs managment.

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['day']) && !empty($REQUEST['viewlog'])) {
    #
    # Viewing of daily log file
    #
    $output = '';
    foreach ($REQUEST['viewlog'] as $logfile) {
        if (substr($logfile, -3) == '.gz')
             $content = gzfile_get_contents(LOGS.$logfile);
        else $content = file_get_contents(LOGS.$logfile);
        $output .= CMS::call('PARSER')->parseText('[quote='.$logfile.']'.$content.'[/quote]');
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'log.tpl');
    $TEMPLATE->set('text', $output);
    echo $TEMPLATE->parse();

} elseif (!empty($REQUEST['archive']) && !empty($REQUEST['viewlog'])) {
    #
    # Viewing of monthly log archive
    #
    $output = '';
    foreach ($REQUEST['viewlog'] as $logfile) {
        $logfile  = basename($logfile);
        $contents = gzfile_get_contents(LOGS.$logfile);
        $output  .= CMS::call('PARSER')->parseText('[quote='.$logfile.']'.$contents.'[/quote]');
        unlink(LOGS.$logfile);
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'log.tpl');
    $TEMPLATE->set('text', $output);
    echo $TEMPLATE->parse();

} elseif (!empty($REQUEST['month']) && !empty($REQUEST['browse'])) {
    #
    # Viewing the archive for the month
    #
    $browse = basename($REQUEST['browse']);
    #
    # Decompress from tar.gz
    #
    $PHAR = new PharData(LOGS.$browse);
    #
    # Creates file tar
    #
    $PHAR->decompress();

    $PHAR = new PharData(substr(LOGS.$browse, 0, -3));
    $PHAR->extractTo(LOGS);
    #
    # Remove unneeded file tar
    #
    unlink(substr(LOGS.$browse, 0, -3));

    foreach ($PHAR as $file) {
        $list[] = $file->getFileName();
    }
    unset($PHAR);

    $output = [];
    foreach($list as $key => $file) {
        if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $file, $matches)) {
            $output['days'][$key]['date'] = $matches[1];
            $output['days'][$key]['log']  = $file;
        }
    }
    $output['archive'] = TRUE;

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'logs.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();

} else {
    #
    # Build monthly log archives (except current month)
    #
    if (!empty($REQUEST['build'])) {
        CMS::call('LOG')->logMergeByMonth();
        ShowMessage('Done');
    }
    #
    # Show lists of daily and monthly logs
    #
    $logs   = GetFilesList(LOGS);
    $month  = date('m');
    $year   = date('Y');
    $output = [];
    $remove = [];

    foreach ($logs as $key => $log) {
        if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $log, $matches)) {
            if ($matches[3] < $month) {
                $remove[] = $matches[0];
            }
            $output['days'][$key]['date'] = $matches[1];
            $output['days'][$key]['log']  = $log;
        }
        if (preg_match("/^((.*?)-(.*?))\.tar(|.gz)$/i", $log, $matches)) {
            $output['months'][$key]['date'] = $matches[1];
            $output['months'][$key]['log']  = $log;
        }
    }

    $day = $output['days'];
    foreach ($remove as $file) {
        $mask  = substr($file, 0 ,7);
        foreach ($output['months'] as $key => $value) {
            if (($mask == $value['date']) && ($value['log'] == $mask.'.tar.gz')) {
                foreach ($day as $num => $cont) {
                    if ($cont['log'] == $file) {
                        unset($output['day'][$num]);
                        unlink(LOGS.$file);
                    }
                }
            }
        }
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'logs.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();
}
