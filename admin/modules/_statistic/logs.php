<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistics
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['day']) && !empty($REQUEST['viewlog'])) {
    # Viewing of daily log file
    $output = '';
    foreach ($REQUEST['viewlog'] as $logfile) {
        $logfile = basename($logfile);
        if (substr($logfile, -3) == '.gz')
             $contents = gzfile_get_contents(LOGS.$logfile);
        else $contents = file_get_contents(LOGS.$logfile);

        $output .= CMS::call('PARSER')->parseText('[quote='.$logfile.']'.$contents.'[/quote]');
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'log.tpl');
    echo $TPL->parse(array('text' => $output));

} elseif (!empty($REQUEST['archive']) && !empty($REQUEST['viewlog'])) {
    # Viewing of monthly log archive
    $archive = basename($REQUEST['archive']);
    if (is_readable(LOGS.$archive)) {
        $output['text'] = '';
        foreach ($REQUEST['viewlog'] as $logfile) {
            $logfile = basename($logfile);
            $contents = gzfile_get_contents(LOGS.$logfile);
            $output['text'] .= CMS::call('PARSER')->parseText('[quote='.$logfile.']'.$contents.'[/quote]');
        }
    }

    $TPL = new TEMPLATE(dirname(__FILE__).DS.'log.tpl');
    echo $TPL->parse($output);

} elseif (!empty($REQUEST['month']) && !empty($REQUEST['browse'])) {

    # Viewing the archive for the month
    $browse = basename($REQUEST['browse']);
    if (is_readable(LOGS.$browse)) {
        # Decompress from gz
        $PHAR = new PharData(LOGS.$browse);
        $PHAR->decompress();                       # Creates file.tar
        unset($PHAR);

        $PHAR = new PharData(substr(LOGS.$browse, 0, -3));
        $PHAR->extractTo(LOGS);
        unlink(substr(LOGS.$browse, 0, -3));    # Remove unneeded file.tar

        foreach ($PHAR as $file) {
            $list[] = $file->getFileName();
        }
        unset($PHAR);

        $output = [];
        foreach($list as $key => $file) {
            if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $file, $matches)) {
                $output['day'][$key]['date'] = $matches[1];
                $output['day'][$key]['log']  = $file;
            }
        }
        $output['archive'] = $browse;

        $TPL = new TEMPLATE(dirname(__FILE__).DS.'logs.tpl');
        echo $TPL->parse($output);
    }
} else {
    # Build monthly log archives (except current month)
    if (!empty($REQUEST['build'])) {
        CMS::call('LOG')->logMergeByMonth();
        ShowMessage('Done');
    }
    # Show lists of daily and monthly logs
    $logs   = GetFilesList(LOGS);
    $month  = date('m');
    $year   = date('Y');
    $output = [];
    $remove = [];

    foreach ($logs as $key => $log_entry) {
        if (preg_match("/^((.*?)-(.*?)-(.*?))\.log(|.gz)$/i", $log_entry, $matches)) {
            if ($matches[3] < $month) {
                $remove[] = $matches[0];
            }
            $output['day'][$key]['date'] = $matches[1];
            $output['day'][$key]['log']  = $log_entry;
        }

        if (preg_match("/^((.*?)-(.*?))\.tar(|.gz)$/i", $log_entry, $matches)) {
            $output['month'][$key]['date'] = $matches[1];
            $output['month'][$key]['log']  = $log_entry;
        }
    }

    $day = $output['day'];
    foreach ($remove as $file) {
        $mask  = substr($file, 0 ,7);
        foreach ($output['month'] as $key => $value) {
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

    $TPL = new TEMPLATE(dirname(__FILE__).DS.'logs.tpl');
    echo $TPL->parse($output);
}
