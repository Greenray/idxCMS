<?php
# idxCMS Flat Files Content Management Sysytem

/** Read and save database files.
 * @file      system/index.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

class INDEX {

    /** Name of the index file.
     * @var string
     */
    protected $index = 'index';

    /** Class initialization. */
    protected function __construct() {}

    /** Prevent to clone object. */
    protected function __clone() {}

    /** Sets the name of the serialized file.
     * The default name is 'index'.
     * @param  string $name Name of the index file
     * @return void
     */
    protected function setIndex($name) {
        $this->index = $name;
    }

    /** Gets the data from the index file.
     * @param  string $path Path to index file
     * @return array        Unserialised content of the index file
     */
    public function getIndex($path) {
        return GetUnserialized($path.$this->index);
    }

    /** Writes the index file with serialization of data.
     * @param  string  $path  Path to index file
     * @param  array   $array Data for saving
     * @return boolean        The result of the operation
     */
    protected function saveIndex($path, $array) {
        return file_put_contents($path.$this->index, serialize($array), LOCK_EX);
    }

    /** Calculate the new ID of the data for the index file.
     * @param  array   $array For this array we need new ID for the new data
     * @return integer        Calculated ID
     */
    protected function newId($array) {
        if (empty($array)) {
            return 1;
        }
        $tmp = array_keys($array);
        sort($tmp);
        return end($tmp) + 1;
    }
}
