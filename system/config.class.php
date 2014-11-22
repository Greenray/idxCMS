<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# SYSTEM - CONFIG

/** The Config Class.
 *
 * Works with configuration.
 * @package   idxCMS
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @link      https://github.com/Greenray/idxCMS/system/config.class.php
 */
final class CONFIG {

    /**
     * Configuration filename.
     * @var string
     */
    private static $ini = '';

    /**
     * Site configuration data.
     * @var array
     */
    private static $config = array();       #

    /**
     * Class initialization.
     * Sets config filename, reads and parses config data.
     */
    public function __construct() {
        self::$ini = CONTENT.'config.ini';
        self::$config = parse_ini_file(self::$ini, TRUE);
    }

    /**
     * Creates the configuration section.
     * Sets the parameters and their values in the specified config section.
     * @param  string $section Name of the config section
     * @param  array  $values  Config parameter = value for the current section
     * @return void
     */
    public static function setSection($section, $values) {
        self::$config[$section] = $values;
    }

    /**
     * Gets parameters with their values from the specified config section.
     * @param  string $section Name of the config section
     * @return array  Config parameters = values for the current section or empty array
     */
    public static function getSection($section) {
        return empty(self::$config[$section]) ? array() : self::$config[$section];
    }

    /**
     * Removes specified config section.
     * @param  string $section Name of the config section
     * @return void
     */
    public static function unsetSection($section) {
        if (!empty(self::$config[$section]))
            unset(self::$config[$section]);
    }

    /**
     * Sets the parameter with its value for the specified config section.
     * @param  string $section Name of the config section
     * @param  string $param   Name of the config parameter
     * @param  mixed  $value   Value of the specified parameter
     * @return void
     */
    public static function setValue($section, $param, $value) {
        self::$config[$section][$param] = $value;
    }

    /**
     * Gets the parameter with its value from the specified config section.
     * @param  string $section Name of the config section
     * @param  string $param   Name of the config parameter
     * @return mixed  Value of the specified parameter
     */
    public static function getValue($section, $param) {
        return empty(self::$config[$section][$param]) ? FALSE : self::$config[$section][$param];
    }

    /**
     * Saves the config file.
     * @return boolean The result of the operation
     */
    public function save() {
        $ini = '';
        # Create section
        foreach (self::$config as $id => $section) {
            $ini .= '['.$id.']'.LF;
            # Create parameter = "value" in the section
            foreach ($section as $key => $value) {
                if (!is_array($value)) {
                    $ini .= $key.' = "'. str_replace('"', '&quot;', $value).'"'.LF;
                } else {
                    # Create array of parameter = "value" in the section
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
