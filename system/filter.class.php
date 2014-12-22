<?php
/**
 * @file      system/filter.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

/** Class FILTER - Clean parameters $_REQUEST, $_FILES, $_COOKIE, detect intrusions and ban unwanted visitors. */

final class FILTER {

    /** Array of filtered $_POST, $_GET and $_FILES parameters.
     * @param array
     */
    private static $REQUEST = [];

    /** Array of filtered $_COOKIE parameters.
     * @param array
     */
    private static $COOKIE = [];

    /** Array of parameters types.
     * @param array
     */
    private static $types = ['REQUEST', 'FILES', 'COOKIE'];

    /** Class initialization. */
    public function __construct() {}

    /** Prevent to clone object. */
    public function __clone() {}

    /**
     * Clean all parameters and their values of the request array and
     * transforme them into the internal encoding of the system = UTF-8.
     * @param  array $value Input array of parameters.
     * @return string       Filered parameters.
     */
    private function cleanValue($value) {
        $value = trim($value);
        // Check for magic quotes and remove them if necessary.
        if (function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()) {
            $value = preg_replace('(\\\(["\'/]))im', '$1', $value);
        }
        $encode = mb_convert_variables(mb_internal_encoding(), "ASCII,Windows-1251,UTF-8", $value);
        $value = strip_tags($value);
        $value = stripslashes($value);
        return UnifyBr($value);
    }

    /** Clean variables.
     * @param  array $vars Input array of parameters.
     * @return array       Filered values of array parameters.
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

    /** Main function.
     * Detect intrusion, clean and unset $_POST, $_GET, $_FILES and $_COOKIE.
     * The result are two variables: $REQUEST and $COOKIE.
     * @return void
     */
    public function sanitaze() {
        $this->ids();
        foreach(self::$types as $VAR) {
            $$VAR = $this->clear($GLOBALS['_'.$VAR]);
            unset($GLOBALS['_'.$VAR]);
        }
        self::$REQUEST = array_merge($REQUEST, $FILES);
        self::$COOKIE  = $COOKIE;
    }

    /** Get all filtered parameter of the specified type.
     * @param  string $type Type of parameter.
     * @return array        Array of filtered parameters of the specified type.
     */
    public static function getAll($type) {
        return self::$$type;
    }

    /** Get specified filtered parameter.
     * @param  string $type  - Type of parameter.
     * @param  string $param - Name of parameter.
     * @return array|string  - Value of parameter or empty string.
     */
    public static function get($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            $value = self::$$type;
            return $value[$param];
        }
        return '';
    }

    /** Remove filtered parameter.
     * @param  string $type  Type of parameter.
     * @param  string $param Name of parameter.
     * @return void
     */
    public static function remove($type, $param) {
        if (array_key_exists($param, self::$$type)) {
            unset(self::${$type}[$param]);
        }
    }

    /** Email validation.
     * @param  string $email Email address.
     * @return boolean       The result of operation.
     */
    public function validEmail($email) {
        return preg_match('/^([a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*)@((([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))\.)*(([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))$/', $email) ? TRUE : FALSE;
    }

    /** Ban user.
     * @return boolean The result of operation.
     * @todo Log the result.
     */
    public function ban() {
        $bans = file_exists(CONTENT.'bans') ? file_get_contents(CONTENT.'bans') : '';
        if (strpos($bans, self::$REQUEST['host']) === FALSE) {
            return file_put_contents(CONTENT.'bans', $bans.self::$REQUEST['host'].LF);
        }
    }

    /** Intrusion detection.
     * If the intrusion will be detected this event will be logged and the system will die.\n
     * In current it detected:
     *  - bad words in $_SERVER;
     *  - banned IP or cookie;
     *  - malicious URL requests.
     * @return void
     */
    private function ids() {
        # Bad words in $_SERVER
        $ids = [
            'base', 'benchmark', 'concat', 'document.cookie', 'eval', 'echo', 'etc/passwd', 'etc/shadow', 'insert', 'into', 'select', 'substr', 'union',
            'script','iframe','applet','object','alert','embed','img','style'
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

        # Ban check.
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

        # Protection against malicious URL requests.
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
