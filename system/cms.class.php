<?php
/**
 * Registers objects and provides access to their methods.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/cms.class.php
 * @package   Core
 */

class CMS {

    /** @var array Registered objects */
    public static $obj = [];

    /** Class initialization */
    private function __construct() {}

    /** Prevent to clone object */
    private function __clone() {}

    /**
     * Creates and registers the object.
     *
     * @param  string $class Class name
     * @return object        Created and initialized object
     */
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    /**
     * Calls the object, if object is not set it will be created.
     * <pre>
     * Examples:
     *     $CMS = CMS::call('SYSTEM');                - when you need to initialize the object "by hands"
     *     $CMS = CMS::call('SYSTEM')->initModules(); - call an object method
     * </pre>
     *
     * @param  string $class Class name
     * @return object        Existing or created and initialized object
     */
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    /**
     * Removes the object.
     *
     * @param string $class Class name
     */
    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
