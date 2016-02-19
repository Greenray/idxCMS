<?php
/**
 * Reads and saves database files.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.2
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/dbase.class.php
 * @package   Core
 */

class DBASE {

    /** @var integer ID of the current category */
    protected $category;

    /** @var array Comments for article, topic, image and so on */
    protected $comments = [];

    /** @var string Current module */
    protected $container = '';

    /** @var array Content of the current category */
    protected $content = [];

    /** @var string Filename of the item with short description */
    protected $desc = 'desc';

    /** @var string Name of the index file */
    protected $index = 'index';

    /** @var integer ID of the article, topic, image and so on */
    protected $item;

    /** @var string Current module which uses this class */
    protected $module = '';

    /** @var string ID of the current section */
    protected $section = '';

    /** @var array Sections of the carrent module */
    protected $sections = [];

    /** @var string Item filename with full text */
    protected $text = 'text';

    /** Class initialization */
    protected function __construct() {}

    /**
     * Sets the name of the serialized file.
     *
     * @param  string $name Name of the index file
     */
    protected function setIndex($name) {
        $this->index = $name;
    }

    /**
     * Gets the data from the index file.
     *
     * @param  string $path Path to index file
     * @return array        Unserialised content of the index file or empty array
     */
    public function getIndex($path) {
        if (file_exists($path.$this->index)) {
            return json_decode(file_get_contents($path.$this->index), TRUE);
        } else {
            return [];
        }
    }

    /**
     * Writes the index file with serialization of data.
     *
     * @param  string    $path  Path to index file
     * @param  array     $array Data for saving
     * @throws Exception "Cannot save index" No access rights
     * @return boolean   The result of the operation
     */
    protected function saveIndex($path, $array) {
        return file_put_contents($path.$this->index, json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    /**
     * Calculates the new ID of the data for the index file.
     *
     * @param  array   $array For this array we need new ID for the new data
     * @return integer        Calculated ID
     */
    protected function newId($array = []) {
        if (empty($array)) {
            return 1;
        }
        $id = array_keys($array);
        sort($id);
        return end($id) + 1;
    }
}
