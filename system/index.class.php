<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com

# Saving and reading database files.
class INDEX {

    protected $index = 'index';      # Name of the index file

    private function __construct() {}
    private function __clone() {}
    
    protected function setIndex($name) {
        $this->index = $name;
    }

    public function getIndex($path) {
        return GetUnserialized($path.$this->index);
    }

    protected function saveIndex($path, $array) {
        return file_put_contents($path.$this->index, serialize($array), LOCK_EX);
    }

    # Calculate new ID
    protected function newId($array) {
        if (empty($array)) {
            return 1;
        }
        $tmp = array_keys($array);
        sort($tmp);
        return end($tmp) + 1;
    }
}
?>