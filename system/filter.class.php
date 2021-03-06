<?php
/**
 * Cleans parameters $_REQUEST, $_FILES, $_COOKIE, detect intrusions and ban unwanted visitors.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/filter.class.php
 * @package   Core
 */

final class FILTER {

    /** @var array Array of filtered $_POST, $_GET and $_FILES parameters */
    public static $REQUEST = [];

    /** @var array Array of filtered $_COOKIE parameters */
    private static $COOKIE = [];

    /** @var array Array of parameters types */
    private static $types = ['REQUEST', 'FILES', 'COOKIE'];

    /** Class initialization */
    public function __construct() {}

    /** Prevent to clone object */
    public function __clone() {}

    /**
     * Cleans all parameters and their values of the request array and
     * transforme them into the internal encoding of the system = UTF-8.
     *
     * @param  array  $value Input array of parameters
     * @return string        Filered parameters
     */
    private function cleanValue($value) {
        $value = trim($value);
        #
        # Check for magic quotes and remove them if necessary
        #
        if (function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()) {
            $value = preg_replace('(\\\(["\'/]))im', '$1', $value);
        }
        $value = strip_tags($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return UnifyBr($value);
    }

    /**
     * Cleans variables.
     *
     * @param  array $vars Input array of parameters
     * @return array       Filtered values of parameters
     */
    private function clear($vars) {
        $result = [];

        foreach($vars as $key => $value) {
            if (!is_array($value)) {
                $result[$this->cleanValue($key)] = $this->cleanValue($value);
            } else {
                $clear = [];
                foreach($value as $item => $field) {
                    $clear[$this->cleanValue($item)] = $this->cleanValue($field);
                }
                $result[$this->cleanValue($key)] = $clear;
            }
        }
        return $result;
    }

    /**
     * Detects intrusion, clean and unset $_POST, $_GET, $_FILES and $_COOKIE.
     * The result are two variables: $REQUEST and $COOKIE.
     */
    public function sanitate() {
        $this->ids();

        foreach(self::$types as $VAR) {
            $$VAR = $this->clear($GLOBALS['_'.$VAR]);
            unset($GLOBALS['_'.$VAR]);
        }

        self::$REQUEST = array_merge($REQUEST, $FILES);
        self::$COOKIE  = $COOKIE;
    }

    /**
     * Gets all filtered parameter of the specified type.
     *
     * @param  string $type Type of parameter
     * @return array        Array of filtered parameters of the specified type
     */
    public static function getAll($type) {
        return self::$$type;
    }

    /**
     * Gets specified filtered parameter.
     *
     * @param  string $type  Type of parameter
     * @param  string $param Name of parameter
     * @return array|boolean Parameter values or FALSE
     */
    public static function get($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            $value = self::$$type;
            return $value[$param];
        }
        return FALSE;
    }

    /**
     * Removes filtered parameter.
     *
     * @param  string $type  Type of parameter
     * @param  string $param Name of parameter
     */
    public static function remove($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            unset(self::${$type}[$param]);
        }
    }

    /**
     * Email validation.
     *
     * @param  string  $email Email address
     * @return boolean        The result of operation
     */
    public function validEmail($email) {
        return preg_match('/^([a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*)@((([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.) {3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))\.)*(([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.) {3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))$/', $email) ? TRUE : FALSE;
    }

    /**
     * Bans user.
     *
     * @return boolean The result of operation
     */
    public function ban() {
        $bans = file_exists(CONTENT.'bans') ? file_get_contents(CONTENT.'bans') : '';
        if (strpos($bans, self::$REQUEST['host']) === FALSE) {
            return file_put_contents(CONTENT.'bans', $bans.self::$REQUEST['host'].LF, LOCK_EX);
        }
    }

    /**
     * Intrusion detection.
     * In current it can detect:
     *  - bad words in $_SERVER;
     *  - malicious URL requests;
     *  - banned IP or cookie.
     * If the intrusion will be detected this event will be logged and the system will die.
     */
    private function ids() {
        #
        # Bad words in $_SERVER
        #
        $ids = [
            'alert', 'applet', 'base', 'benchmark', 'concat', 'document.cookie', 'echo', 'embed', 'etc/passwd', 'etc/shadow',
            'eval', 'iframe', 'img', 'insert', 'into', 'null', 'object', 'order by','order+by', 'script', 'select', 'substr',
            'style', 'union', '<', '>', '</', '/>'
        ];
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
        #
        # Ban check
        #
        $bans = file_exists(CONTENT.'bans') ? file(CONTENT.'bans', FILE_IGNORE_NEW_LINES) : [];

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

        foreach(self::$types as $var) {
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
        $info .= 'Request: '.$result.LF;
        #
        # Protection against malicious URL requests
        #
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
