<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com

# Registeres classes and provides access to there methods.
class CMS {

    private static $obj = array();      # Registered objects.

    private function __construct() {}
    private function __clone() {}

    # Creating and registering of object.
    public static function register($id) {
        $param = explode(':', $id);
        $class = $param[0];
        self::$obj[$id] = new $class();
        return self::$obj[$id];
    }

    # Calling of object, if object is not set it'll be created.
    public static function call($id) {
        return empty(self::$obj[$id]) ? self::register($id) : self::$obj[$id];
    }

    public static function remove($id) {
        if (!empty(self::$obj[$id])) {
            unset(self::$obj[$id]);
        }
    }
}
?>