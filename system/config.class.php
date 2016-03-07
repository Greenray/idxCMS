<?php
/**
 * Processing of configuration.
 * idxCMS config file is look like this:
 * <pre>
 *   [main]                    - Section
 *   title  = "idxCMS"         - Parameter   = "Value"
 *   name   = "flat.CMS"       - Parameter   = "Value" (value is combined)
 *   0[]    = "index"          - Parameter[] = "Value" (parameter is array)
 *   [output.Default]          - Section (combined)
 *   left[] = "posts.calendar" - Parameter[] = "Value" (parameter is array and value is combined)
 *   and so on
 * </pre>
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/config.class.php
 * @package   Core
 */

class CONFIG {

    /** @var string Main Configuration file */
    private static $ini = 'config.ini';

    /** @var array Site configuration data */
    private static $config = [];

    /**
     * Class initialization.
     * Sets config filename, reads and parses config data.
     */
    public function __construct() {
        self::$ini    = CONTENT.self::$ini;
        self::$config = parse_ini_file(self::$ini, TRUE);
    }

    /**
     * Gets parameters with their values from the specified config section.
     *
     * @param  string $section Name of the config section
     * @return array           Config parameters = values for the current section or empty array
     */
    public static function getSection($section) {
        return empty(self::$config[$section]) ? [] : self::$config[$section];
    }

    /**
     * Gets the parameter with its value from the specified config section.
     *
     * @param  string $section Name of the config section
     * @param  string $param   Name of the config parameter
     * @return array|FALSE     Value of the specified parameter or FALSE
     */
    public static function getValue($section, $param) {
        return empty(self::$config[$section][$param]) ? FALSE : self::$config[$section][$param];
    }

    /**
     * Creates the configuration section.
     * Sets the parameters and their values in the specified config section.
     *
     * @param string $section Name of the config section
     * @param array  $values  Config "parameter = value" for the current section
     */
    public static function setSection($section, $values) {
        self::$config[$section] = $values;
    }

    /**
     * Sets the parameter with its value for the specified config section.
     *
     * @param string $section Name of the config section
     * @param string $param   Name of the config parameter
     * @param mixed  $value   Value of the specified parameter
     */
    public static function setValue($section, $param, $value) {
        self::$config[$section][$param] = $value;
    }

    /**
     * Removes specified config section.
     *
     * @param string $section Name of the config section
     */
    public static function unsetSection($section) {
        if (!empty(self::$config[$section])) unset(self::$config[$section]);
    }

    /**
     * Creates and saves the config file.
     * It can create sections with parameters and sections where parameter is an array.
     *
     * @return boolean The result of the operation
     */
    public function save() {
        $ini = '';
        #
        # Create section
        #
        foreach (self::$config as $id => $section) {
            $ini .= '['.$id.']'.LF;
            #
            # Create parameter = "value" in the section
            #
            foreach ($section as $key => $value) {
                if (!is_array($value)) {
                    $ini .= $key.' = "'. str_replace('"', '&quot;', $value).'"'.LF;
                } else {
                    #
                    # Create array of parameter = "value" in the section
                    #
                    foreach ($value as $i => $item) {
                        if (!is_array($item)) {
                            $ini .= $key.'['.$i.'] = "'. str_replace('"', '&quot;', $item).'"'.LF;
                        } else {
                            foreach ($item as $j => $point) {
                                if (!empty($point)) {
                                    $ini .= $key.'['.$j.'] = "'. str_replace('"', '&quot;', $point).'"'.LF;
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
