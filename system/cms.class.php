<?php
/**
 * @file      system/cms.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/cms.class.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

/** @class CMS
 * Register objects and provide access to their methods.
 */
class CMS {

    /** Registered objects.
     * @param array
     */
    private static $obj = array();

    /** Class initialization */
    private function __construct() {}
    /** Prevent to clone object */
    private function __clone() {}

    /** Create and register the object.
     * @param  string $class Class name
     * @return object - Created and initialized object
     */
    public static function register($class) {
        self::$obj[$class] = new $class();
        return self::$obj[$class];
    }

    /** Call the object, if object is not set it'll be created.
     * @param  string $class Class name
     * @return object - Existing or created and initialized object\n
     * Example:
     * @code
     * $CMS = CMS::call('SYSTEM');
     * @endcode
     * or
     * @code
     * CMS::call('SYSTEM')->initModules();
     * @endcode
     */
    public static function call($class) {
        return empty(self::$obj[$class]) ? self::register($class) : self::$obj[$class];
    }

    /** Remove the object.
     * @param  string $class Class name
     * @return nothing
     */
    public static function remove($class) {
        if (isset(self::$obj[$class])) {
            unset(self::$obj[$class]);
        }
    }
}
