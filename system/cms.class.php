<?php
/**
 * @file      system/cms.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

/** Class CMS - Register objects and provide access to their methods. */

class CMS {

    /** Registered objects.
     * @var array
     */
    private static $obj = [];

    /** Class initialization. */
    private function __construct() {}

    /** Prevent to clone object. */
    private function __clone() {}

    /** Create and register the object.
     * @param  string $class Class name.
     * @return object        Created and initialized object.
     */
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    /** Call the object, if object is not set it will be created.
     * @code
     * $CMS = CMS::call('SYSTEM'); - when it is need to initialize variable.
     * CMS::call('SYSTEM')->initModules(); - when the return value is not needed.
     * @endcode
     * @param  string $class Class name.
     * @return object        Existing or created and initialized object.
     */
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    /** Remove the object.
     * @param  string $class Class name.
     * @return void
     */
    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
