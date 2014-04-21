<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com

class CONFIG {

    private static $ini    = '';            # Configuration file
    private static $config = array();       # Site configuration.

    public function __construct() {
        self::$ini = CONTENT.'config.ini';
        self::$config = parse_ini_file(self::$ini, TRUE);
    }

    public static function setSection($section, $values) {
        self::$config[$section] = $values;
    }

    public static function getSection($section) {
        return empty(self::$config[$section]) ? array() : self::$config[$section];
    }

    public static function unsetSection($section) {
        if (!empty(self::$config[$section]))
            unset(self::$config[$section]);
    }

    public static function setValue($section, $param, $value) {
        self::$config[$section][$param] = $value;
    }

    public static function getValue($section, $param) {
        return empty(self::$config[$section][$param]) ? FALSE : self::$config[$section][$param];
    }

    public function save() {
        $ini = '';
        foreach (self::$config as $id => $section) {
            $ini .= '['.$id.']'.LF;
            foreach ($section as $key => $value) {
                if (!is_array($value)) {
                    $ini .= $key.' = "'. str_replace('"', '&quot;', $value).'"'.LF;
                } else {
/*
                    # This is for PHP > 5.2
                    foreach ($value as $i => $item) {
                        if (!is_array($item))
                            $ini .= $key.'['.$i.'] = "'. str_replace('"', '&quot;', $item).'"'.LF;
                        else {
                            foreach ($item as $j => $point) {
                                if (!empty($point))
                                    $ini .= $key.'['.$i.']['.$j.'] = "'. str_replace('"', '&quot;', $point).'"'.LF;
                            }
                        }
                    }
*/
                    foreach ($value as $i => $item) {
                        if (!is_array($item)) {
                            $ini .= $key.'[] = "'. str_replace('"', '&quot;', $item).'"'.LF;
                        } else {
                            foreach ($item as $j => $point) {
                                if (!empty($point)) {
                                    $ini .= $key.'[] = "'. str_replace('"', '&quot;', $point).'"'.LF;
                                }
                            }
                        }
                    }
                }
            }
        }
        return file_put_contents(self::$ini, $ini, LOCK_EX);
    }
}
?>