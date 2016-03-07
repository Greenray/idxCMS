<?php
/**
 * Logging.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/log.class.php
 * @package   Core
 */

class LOG {

    /** Class initialization */
    public function __construct() {}

    /**
     * Writes error message into log file.
     *
     * @param  string $message Error message
     * @param  string $info    Additioinal info
     * @return boolean FALSE
     */
    public function logError($message, $info = '') {
        file_put_contents(LOGS.'error.log', $message.' '.$info.LF, LOCK_EX);
        return FALSE;
    }

    /**
     * Registers users logins into log file.
     *
     * @param  string  $type    Message type
     * @param  string  $user    Username
     * @param  string  $message Message
     * @return boolean FALSE
     */
    public static function logPut($type, $user, $message) {
        $entry = date('d-m-Y H:i:s', time()).' '.$type.' ';
        if (!empty($user))
             $entry .= '('.$user.' from '.$_SERVER['REMOTE_ADDR'].') '.$message.LF;
        else $entry .= $message.LF;
        gzfile_put_contents(LOGS.date('Y-m-d', time()).'.log.gz', $entry, 'a');
        return FALSE;
    }

    /**
     * Сreates tar archive of logs for the month.
     *
     * @param  string  $title       Filename
     * @param  integer $day         Date
     * @param  integer $month       Month
     * @param  integer $year        Year
     * @param  integer $first_month The first month of the year
     * @param  integer $first_year  The first year of the age
     * @return boolean TRUE
     */
    public static function logMerge($title, $day, $month, $year, $first_month = 1, $first_year = 1980) {
        $logs  = GetFilesList(LOGS);
        $start = mktime(0, 0, 0, $first_month, 1, $first_year);
        $today = mktime(0, 0, 0, $month, $day, $year);
        $to_merge = [];
        foreach ($logs as $log_entry) {
            if (preg_match("/^(.*?)-(.*?)-(.*?)\.log(|.gz)$/i", $log_entry, $matches)) {
                $c = mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
                if ($c >= $start && $c <= $today) {
                    $to_merge[] = $log_entry;
                }
            }
        }
        if (!empty($to_merge)) {
            try {
                $PHAR = new PharData(LOGS.$title.'.tar');
                foreach ($to_merge as $file) {
                    $PHAR->addFile(LOGS.$file, $file);
                }
                $PHAR->compress(Phar::GZ);
            } catch (Exception $error) {
                SYSTEM::showError($error->getMessage());
            }
            foreach ($to_merge as $file) {
                    unlink(LOGS.$file);
            }
            unlink(LOGS.$title.'.tar');
        }
        return TRUE;
    }

    /**
     * Prepares daily log files to create a single file per month.
     *
     * @return boolean TRUE
     */
    public static function logMergeByMonth() {
        $logs   = GetFilesList(LOGS);
        $month  = date('m');
        $year   = date('Y');
        $merged = [];
        foreach ($logs as $log_entry) {
            if (preg_match("/^(.*?)-(.*?)-(.*?)\.log(|.gz)$/i", $log_entry, $matches)) {
                if (!in_array($matches[1].'-'.$matches[2], $merged) &&
                    (($matches[2] < $month) || ($matches[1] < $year))) {
                        self::logMerge(
                            $matches[1].'-'.$matches[2],
                            date('t', mktime(0, 0, 0, $matches[2], $matches[3], $matches[1])),
                            $matches[2],
                            $matches[1],
                            $matches[2],
                            $matches[1]
                    );
                    $merged[] = $matches[1].'-'.$matches[2];    # Already processed files.
                }
            }
        }
        return TRUE;
    }
}
