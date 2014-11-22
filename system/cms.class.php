<?php
# idxCMS version 2.3
# Copyright (c) 2014 Victor Nabatov aka Greenray greenray.spb@gmail.com
# SYSTEM - CMS

/** The CMS Class.
 *
 * Registers classes and provides access to their methods.
 * @package   idxCMS
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @link      https://github.com/Greenray/idxCMS/system/cms.class.php
 */
class CMS {

    /**
     * Registered objects.
     * @var array
     */
    private static $obj = array();

    /** Class initialization */
    private function __construct() {}
    private function __clone() {}

    /**
     * Creating and registering of object.
     * @param  string $class Class name
     * @return object Created and initialized object
     */
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    /**
     * Calling of object, if object is not set it'll be created.
     * @param  string $class Class name
     * @return object Existing or created and initialized object
     */
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    /**
     * Removing of object.
     * @param  string $class Class name
     * @return void
     */
    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
