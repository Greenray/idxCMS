<?php
/**
 * @file      system/uploader.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

/** Class UPLOADER - Uploads files. */

class UPLOADER {

    /** Directory for uploaded files.
     * @param string
     */
    private $upload_dir;

    /** Max size for uploading files.
     * @param integer
     */
    private $max_size;

    /** Size of uploading file.
     * @param integer
     */
    private $file_size;

    /** Temorary file.
     * @param string
     */
    private $file_tmp;

    /** Type of the file.
     * @param string
     */
    private $file_type;

    /** Allowed types of file for uploading.
     * @param array
     */
    private $allowed_types = [
        'application/zip',
        'application/rar',
        'application/arj',
        'application/tar',
        'application/x-gzip',
        'application/bzip2',
        'application/pdf',
        'audio/mp3'
    ];

    /** Class initialization.
     * @param  string  $upload_dir Directory for uploading of files
     * @param  integer $max_size   Max size for uploading files
     * @return void
     */
    public function __construct($upload_dir, $max_size = '') {
        $this->upload_dir = $upload_dir;
        $this->max_size   = empty($max_size) ? CONFIG::getValue('main', 'file-max-size') : $max_size;
    }

    /** Set file parameters.
     * @param  array $file Array of file parameters
     * @return void
     */
    private function setFile($file) {
        # Letters and numbers are settled only, spaces are replaced with a sign "_",
        $this->file_name = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $file['name']));
        $this->file_size = $file['size'];
        $this->file_tmp  = $file['tmp_name'];
        $this->file_type = $file['type'];
    }

    /** Check the file.
     * @return boolean   TRUE if the file is valid
     * @throws Exception Your file is not allowed or is corrupted
     * @throws Exception File is too large
     */
    private function checkFile() {
        if (!in_array($this->file_type, $this->allowed_types)) {
            throw new Exception('Your file is not allowed or is corrupted');
        }
        if ($this->file_size >= $this->max_size) {
            throw new Exception('File is too large');
        }
        return TRUE;
    }

    /** Check if the file is acceptable and upload it to upload directory.
     * @param  array $file Data from global variable $_FILES
     * @return array       Name of file and file size
     * @throws Exception   Error of file uploading
     */
    public function upload($file) {
        if ($file['error'] === '0') {
            $this->setFile($file);
            $this->checkFile();
            if (is_uploaded_file($this->file_tmp)) {
                if (move_uploaded_file($this->file_tmp, $this->upload_dir.$this->file_name)) {
                    return array($this->file_name, $this->file_size);
                }
            }
        }
        throw new Exception('Error of file uploading');
    }
}
