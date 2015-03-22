<?php
# idxCMS Flat Files Content Management Sysytem

/** Registers objects and provides access to their methods.
 *
 * @file      system/cms.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

class CMS {

    /** Registered objects.
     * @var array
     */
    private static $obj = [];

    /** Class initialization. */
    private function __construct() {}

    /** Prevent to clone object. */
    private function __clone() {}

    /** Creates and registers the object.
     *
     * @param  string $class Class name
     * @return object        Created and initialized object
     */
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    /** Calls the object, if object is not set it will be created.
     * <pre>
     * Examples:
     *     $CMS = CMS::call('SYSTEM');                - when it is need to initialize object
     *     $CMS = CMS::call('SYSTEM')->initModules(); - calling an object method
     * </pre>
     *
     * @param  string $class Class name
     * @return object        Existing or created and initialized object
     */
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    /** Removes the object.
     *
     * @param  string $class Class name
     * @return void
     */
    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
