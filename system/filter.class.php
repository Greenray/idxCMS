<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com

/** The Filter Class.
 *
 * Cleans parameters of $_POST, $_GET, $_COOKIE, detect intrusions and ban unwanted visitors
 *
 * @package   idxCMS
 * @ingroup   SYSTEM
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            Reloadcms Team http://reloadcms.com
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @file      filter.class.php
 * @link      https://github.com/Greenray/idxCMS/system/filter.class.php
 */
final class FILTER {

    /**
     * Array of filtered $_POST and/or $_GET parameters.
     * @param array
     */
    private static $REQUEST = array();
    /**
     * Array of filtered $_COOKIE parameters.
     * @param array
     */
    private static $COOKIE  = array();
    /**
     * Array of parameters types.
     * @param array
     */
    private $types = array('GET', 'POST', 'FILES', 'COOKIE');

    /** Class initialization */
    public function __construct() {}
    public function __clone() {}

    /**
     * Cleans all keys of the request array.
     * @param  array $input Input array of parameters
     * @return array Filered keys of array of parameters
     */
    private function cleanKey($input) {
        $input = iconv(mb_detect_encoding($input), 'UTF-8//IGNORE', $input);
        $input = strip_tags($input);
        $input = stripslashes($input);
        return str_replace(array("\r\n", "\n\r", "\r", "\n"), '', $input);
    }

    /**
     * Cleans all values of the request array.
     * @param  array $input Input array of parameters
     * @return array Filered values of array parameters
     */
    private function cleanValue($input) {
        $input = iconv(mb_detect_encoding($input), 'UTF-8//IGNORE', $input);
        $input = stripslashes($input);
        return UnifyBr($input);
    }

    /**
     * Cleans variables.
     * @param  array $vars Input array of parameters
     * @return array Filered values of array parameters
     */
    private function clear($vars) {
        $result = array();
        foreach($vars as $key => $value) {
            if (!is_array($value)) {
                $result[$this->cleanKey($key)] = $this->cleanValue($value);
            } else {
                $clear = array();
                foreach($value as $item => $field) {
                    $clear[$this->cleanKey($item)] = $this->cleanValue($field);
                }
                $result[$this->cleanKey($key)] = $clear;
            }
        }
        return $result;
    }

    /**
     * Main function.
     * @return void
     * @uses Intrusion detection system
     */
    public function sanitaze() {
        $this->ids();
        foreach($this->types as $VAR) {
            $$VAR = $this->clear($GLOBALS['_'.$VAR]);
            unset($GLOBALS['_'.$VAR]);
        }
        self::$REQUEST = array_merge($GET, $POST, $FILES);
        self::$COOKIE  = $COOKIE;
    }

    /**
     * Gets all filtered parameter of the specified type.
     * @param  string $type  Type of parameter
     * @return array Array of filtered parameters of the specified type
     */
    public static function getAll($type) {
        return self::$$type;
    }

    /**
     * Gets requested filtered parameter.
     * @param  string $type  Type of parameter
     * @param  string $param Name of parameter
     * @return mixed  Value of parameter
     */
    public static function get($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            $value = self::$$type;
            return $value[$param];
        }
        return '';
    }

    /**
     * Removes filtered parameter.
     * @param  string $type  Type of parameter
     * @param  string $param Name of parameter
     * @return void
     */
    public static function remove($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            unset(self::${$type}[$param]);
        }
    }

    /**
     * Checks if the email is valid.
     * @param  string $email Email address
     * @return boolean
     */
    public function validEmail($email) {
        return preg_match('/^([a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*)@((([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))\.)*(([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))$/', $email) ? TRUE : FALSE;
    }

    /**
     * Ban user.
     * @return boolean
     */
    public function ban() {
        $bans = file_exists(CONTENT.'bans') ? file_get_contents(CONTENT.'bans') : '';
        if (strpos($bans, self::$REQUEST['host']) === FALSE) {
            file_put_contents(CONTENT.'bans', $bans.self::$REQUEST['host'].LF);
        }
    }

    /**
     * Intrusion detection.
     * @return void
     */
    private function ids() {
        $ids = array(
            'base64', 'benchmark', 'concat', 'document.cookie', 'eval', 'echo', 'etc/passwd', 'etc/shadow', 'insert', 'into',
            'select', 'substr', 'union'
        );
        $_SERVER['REQUEST_URI']     = empty($_SERVER['REQUEST_URI'])          ? htmlspecialchars($_SERVER['SCRIPT_NAME']) : htmlspecialchars($_SERVER['REQUEST_URI']);
        $_SERVER['REMOTE_ADDR']     = empty($_SERVER['REMOTE_ADDR'])          ? '0.0.0.0'               : htmlspecialchars($_SERVER['REMOTE_ADDR']);
        $_SERVER['REMOTE_ADDR']     = empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_HOST']     = empty($_SERVER['REMOTE_HOST'])          ? $_SERVER['REMOTE_ADDR'] : htmlspecialchars($_SERVER['REMOTE_HOST']);
        $_SERVER['HTTP_REFERER']    = empty($_SERVER['HTTP_REFERER'])         ? ''       : htmlspecialchars($_SERVER['HTTP_REFERER']);
        $_SERVER['HTTP_USER_AGENT'] = empty($_SERVER['HTTP_USER_AGENT'])      ? 'Hidden' : htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
        if (isset($_COOKIE['UID'])) {
            CMS::call('LOG')->logPut('Note', '', 'Access from the forbidden IP: '.$_SERVER['REMOTE_ADDR']);
            session_destroy();
            die();
        }
        # Ban check
        $bans = file_exists(CONTENT.'bans') ? file(CONTENT.'bans', FILE_IGNORE_NEW_LINES) : array();
        foreach ($bans as $ban) {
            $ban = '/^'.str_replace('*', '(\d*)', str_replace('.', '\\.', trim($ban))).'$/';
            if (preg_match($ban, $_SERVER['REMOTE_ADDR'])) {
                CMS::call('LOG')->logPut('Note', '', 'Access from banned IP: '.$_SERVER['REMOTE_ADDR']);
                session_destroy();
                die('You are banned from this site');
            }
        }
        unset($bans);

        $url  = $_SERVER['REQUEST_URI'];
        $info = 'Remote address: '   .$_SERVER['REMOTE_ADDR'].LF.
                'Suspected URI: '    .$_SERVER['REQUEST_URI'].LF.
                'Suspected referer: '.$_SERVER['HTTP_REFERER'].LF.
                'User agent: '       .$_SERVER['HTTP_USER_AGENT'].LF;

        $result = '';
        foreach($this->types as $var) {
            $result .= $var.': ';
            foreach($GLOBALS['_'.$var] as $key => $value) {
                if (!is_array($value)) {
                    $result .= $key.'='.$value.'|';
                } else {
                    $clear = '';
                    foreach($value as $item => $field) {
                        $clear .= $item.'='.$field.'|';
                    }
                    $result .= $key.'='.$clear.'|';
                }
            }
        }
        $info = 'Request: '.$result.LF;

        # Protection against malicious URL requests
        if (strlen($url) > 255) {
//                $this->ban($_SERVER['REMOTE_ADDR']);
//                setcookie('UID', mt_rand(2, 50), time() + 7200);
                CMS::call('LOG')->logPut('Hack attempt', '', $info);
                header("HTTP/1.1 414 Request-URI Too Long");
                header("Status: 414 Request-URI Too Long");
                header("Connection: Close");
                session_destroy();
                die();
        }

        foreach($ids as $key) {
            if (stristr($url, $key)) {
                $this->ban($_SERVER['REMOTE_ADDR']);
                setcookie('UID', mt_rand(2, 50), time() + 7200);
                CMS::call('LOG')->logPut('Hack attempt', '', $info);
                header("HTTP/1.1 400 Bad Request");
                header("Status: 400 Bad Request");
                header("Connection: Close");
                session_destroy();
                die();
            }
        }
    }
}
