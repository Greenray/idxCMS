<?php
/**
 * @file      system/config.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 */

/**
 * Class CONFIG.
 * Process configuration. idxCMS config file is look like this:
 * <pre>
 * [main]                    Section
 * title = "idxCMS"          Paramemeter = "Value"
 * [navigation]
 * 0[] = "index"             Parameter = "Value" (parameter is an array)
 * [output.Default]          Section (combined)
 * left[] = "posts.calendar" Parameter = "Value" (value is combined)
 * </pre>
 *
 * @package core
 */

class CONFIG {

    /** Main Configuration file.
     * @param string
     */
    private static $ini = 'config.ini';

    /** Site configuration data.
     * @param array
     */
    private static $config = array();

    /**
     * Class initialization.
     * Set config filename, read and parse config data.
     *
     * @return void
     */
    public function __construct() {
        self::$ini = CONTENT.self::$ini;
        self::$config = parse_ini_file(self::$ini, TRUE);
    }

    /**
     * Create the configuration section.
     * Sets the parameters and their values in the specified config section.
     *
     * @param  string $section Name of the config section.
     * @param  array  $values  Config parameter = value for the current section.
     * @return void
     */
    public static function setSection($section, $values) {
        self::$config[$section] = $values;
    }

    /**
     * Get parameters with their values from the specified config section.
     *
     * @param  string $section Name of the config section.
     * @return array           Config parameters = values for the current section or empty array.
     */
    public static function getSection($section) {
        return empty(self::$config[$section]) ? array() : self::$config[$section];
    }

    /**
     * Remove specified config section.
     *
     * @param  string $section Name of the config section.
     * @return void
     */
    public static function unsetSection($section) {
        if (!empty(self::$config[$section])) unset(self::$config[$section]);
    }

    /**
     * Set the parameter with its value for the specified config section.
     *
     * @param  string $section Name of the config section.
     * @param  string $param   Name of the config parameter.
     * @param  mixed  $value   Value of the specified parameter.
     * @return void
     */
    public static function setValue($section, $param, $value) {
        self::$config[$section][$param] = $value;
    }

    /**
     * Get the parameter with its value from the specified config section.
     *
     * @param  string $section Name of the config section.
     * @param  string $param   Name of the config parameter.
     * @return array|FALSE     Value of the specified parameter.
     */
    public static function getValue($section, $param) {
        return empty(self::$config[$section][$param]) ? FALSE : self::$config[$section][$param];
    }

    /**
     * Create and save the config file.
     * It can create sections with parameters and sections where parameter is an array.
     *
     * @return boolean The result of the operation.
     */
    public function save() {
        $ini = '';
        # Create section.
        foreach (self::$config as $id => $section) {
            $ini .= '['.$id.']'.LF;
            # Create parameter = "value" in the section.
            foreach ($section as $key => $value) {
                if (!is_array($value)) {
                    $ini .= $key.' = "'. str_replace('"', '&quot;', $value).'"'.LF;
                } else {
                    # Create array of parameter = "value" in the section.
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
