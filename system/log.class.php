<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com

define('LOGS', CONTENT.'logs'.DS);

final class LOG {

    private static $allow_gz;                   # Allow gzip log files

    public function __construct() {
        self::$allow_gz = extension_loaded('zlib');
    }

    public function logError($message, $info = '') {
        file_put_contents(LOGS.'error.log', $message.' '.$info.LF, LOCK_EX);
        return FALSE;
    }

    public static function logPut($type, $user, $message) {
        $entry = date('d-m-Y H:i:s', time()).' '.$type.' ';
        if (!empty($user))
             $entry .= '('.$user.' from '.$_SERVER['REMOTE_ADDR'].') '.$message.LF;
        else $entry .= $message.LF;
        if (self::$allow_gz)
             gzfile_put_contents(LOGS.date('Y-m-d', time()).'.log.gz', $entry, 'a');
        else file_put_contents(LOGS.date('Y-m-d', time()).'.log', $entry, FILE_APPEND | LOCK_EX);
        return FALSE;
    }

    public static function logMerge($title, $day, $month, $year, $first_month = 1, $first_year = 1980) {
        $logs = GetFilesList(LOGS);
        $start = mktime(0, 0, 0, $first_month, 1, $first_year);
        $today = mktime(0, 0, 0, $month, $day, $year);
        $to_merge = array();
        foreach ($logs as $log_entry) {
            if (preg_match("/^(.*?)-(.*?)-(.*?)\.log(|.gz)$/i", $log_entry, $matches)) {
                $c = mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
                if ($c >= $start && $c <= $today) {
                    $to_merge[] = $log_entry;
                }
            }
        }
        if (!empty($to_merge)) {
            $suffix = self::$allow_gz ? '.gz' : '';
            $TAR = new TAR();
            $TAR->isGzipped = self::$allow_gz;
            $TAR->filename  = LOGS.$title.'.tar'.$suffix;
            $path = getcwd();
            chdir(LOGS);
            foreach ($to_merge as $file) {
                $TAR->addFile($file, substr($file, -3) === '.gz');
            }
            chdir($path);
            if ($TAR->saveTar()) {
                foreach ($to_merge as $file) {
                    unlink(LOGS.$file);
                }
            }
            unset($TAR);
        }
        return TRUE;
    }

    public static function logMergeByMonth() {
        $logs   = GetFilesList(LOGS);
        $month  = date('m');
        $year   = date('Y');
        $merged = array();
        foreach ($logs as $log_entry) {
            if (preg_match("/^(.*?)-(.*?)-(.*?)\.log(|.gz)$/i", $log_entry, $matches)) {
                if (!in_array($matches[1].'-'.$matches[2], $merged) &&
                    (($matches[2] != $month) || ($matches[1] != $year))) {
                        self::logMerge(
                            $matches[1].'-'.$matches[2],
                            date('t', mktime(0, 0, 0, $matches[2], $matches[3], $matches[1])),
                            $matches[2],
                            $matches[1],
                            $matches[2],
                            $matches[1]
                        );
                        $merged[] = $matches[1].'-'.$matches[2];
                }
            }
        }
        return TRUE;
    }
}
?>