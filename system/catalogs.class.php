<?php
/**
 * Works with catalogs (files, links etc.)
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/catalogs.class.php
 * @package   Catalogs
 */

class CATALOGS extends CONTENT {

    /** Class constructor */
    public function __construct() {
        parent::__construct();
        $this->module    = 'catalogs';
        $this->container = CATALOGS;
    }

    /**
     * Uploads file.
     *
     * @param  integer $id   File ID
     * @param  array   $file $_FILES
     * @return array         Name and size of uploaded file
     * @uses class UPLOADER
     */
    public function uploadFile($id, $file) {
        $UPLOAD = new UPLOADER($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        return $UPLOAD->upload($file);
    }
}
