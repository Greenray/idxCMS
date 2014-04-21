<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com

# Registeres classes and provides access to there methods.
class CMS {

    private static $obj = array();      # Registered objects.

    private function __construct() {}
    private function __clone() {}

    # Creating and registering of object.
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    # Calling of object, if object is not set it'll be created.
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
?>