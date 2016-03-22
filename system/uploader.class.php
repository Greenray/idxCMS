<?php
/**
 * Uploads files.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.1 International
 * @file      system/uploader.class.php
 * @package   Core
 */

class UPLOADER {

    /** @var array Allowed types of file for uploading */
    private $allowed_types = [
        'application/zip',
        'application/rar',
        'application/arj',
        'application/tar',
        'application/x-gzip',
        'application/bzip2',
        'application/pdf',
        'audio/mp3',
        'video/avi',
        'application/x-dvi',
        'video/quicktime',
        'video/mpeg'
    ];

    /** @var string Temorary file */
    private $file_tmp;

    /** @var integer Size of uploading file */
    private $file_size;

    /** @var string Type of the file */
    private $file_type;

    /** @var integer Max size for uploading files */
    private $max_size;

    /** @var string Directory for uploaded files */
    private $upload_dir;

    /**
     * Class initialization.
     *
     * @param string  $upload_dir Directory for uploading of files
     */
    public function __construct($upload_dir) {
        $this->upload_dir = $upload_dir;
        $this->max_size   = CONFIG::getValue('main', 'max_filesize');
    }

    /**
     * Checks if the file is acceptable and upload it to upload directory.
     *
     * @param  array     file Data from global variable $_FILES
     * @throws Exception "File is not allowed or is corrupted"
     * @throws Exception "File is too large"
     * @throws Exception "Error of file uploading"
     * @return array     Name of file and sizeof the file
     */
    public function upload($file) {
        if ($file['error'] === '0') {
            #
            # Letters and numbers are settled only, spaces are replaced with a sign "_",
            #
            $this->file_name = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $file['name']));
            $this->file_size = $file['size'];
            $this->file_tmp  = $file['tmp_name'];
            $this->file_type = $file['type'];

            if (!in_array($this->file_type, $this->allowed_types)) {
                throw new Exception('File is not allowed or is corrupted');
            }
            if ($this->file_size > $this->max_size) {
                throw new Exception('File is too large');
            }
            if (is_uploaded_file($this->file_tmp)) {
                if (move_uploaded_file($this->file_tmp, $this->upload_dir.$this->file_name)) {
                    return [$this->file_name, $this->file_size];
                }
            }
        }
        throw new Exception('Error of file uploading');
    }
}
