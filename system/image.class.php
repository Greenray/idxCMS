<?php
/**
 * Uploads and resize images.
 * After uploading it generates thumbnail or icon.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/image.class.php
 * @package   Core
 */

class IMAGE {

    /** @var array Allowed image types */
    private $allowed_types = ['image/gif', 'image/jpeg', 'image/png'];

    /** @var integer The height of the uploading image */
    private $image_height;

    /** @var string The name of the uploading image */
    private $image_name;

    /** @var integer The size of the upoading image */
    private $image_size;

    /** @var string Name of the temporary image */
    private $image_tmp;

    /** @param string Image type */
    private $image_type;

    /** @var integer The width of the uploading image */
    private $image_width;

    /** @var integer Max size for uploading files */
    private $max_size;

    /** @var integer The height of the thumbnail */
    private $thumb_height;

    /** @var integer The width of the thumbnail */
    private $thumb_width;

    /** @var string Directory for uploaded files */
    private $upload_dir;

    /**
     * Class initialization.
     *
     * @param  string  $upload_dir   Name of the upload directory
     * @param  integer $max_size     Image max size
     * @param  integer $thumb_width  Thumbnail width
     * @param  integer $thumb_height Thumbnal height
     */
    public function __construct($upload_dir, $max_size = '', $thumb_width = '', $thumb_height = '') {
        $this->upload_dir   = $upload_dir;
        $this->max_size     = empty($max_size)     ? CONFIG::getValue('main', 'max_filesize') : $max_size;
        $this->thumb_width  = empty($thumb_width)  ? CONFIG::getValue('main', 'thumb_width')  : $thumb_width;
        $this->thumb_height = empty($thumb_height) ? CONFIG::getValue('main', 'thumb_height') : $thumb_height;
    }

    /** Calculates parameters to resize image */
    private function calcResizeParams() {
        $ratio = $this->image_width / $this->image_height;
        if (($this->thumb_width / $this->thumb_height) > $ratio)
             $this->thumb_width  = $this->thumb_height * $ratio;
        else $this->thumb_height = $this->thumb_width / $ratio;
    }

    /**
     * Generates icon.
     *
     * @param  string $name Icon name
     * @return boolean      TRUE if oparation is successful
     */
    public function generateIcon($name = '') {
        $name = empty($name) ? 'icon.png' : $name.'.png';

        self::calcResizeParams();
        $icon = imagecreateTRUEcolor($this->thumb_width, $this->thumb_height);

        switch($this->image_type) {
            case 'image/gif' :
                $img = imagecreatefromgif ($this->upload_dir.$this->image_name);
                imagecolortransparent($img);
                break;

            case 'image/jpeg':
                $img = imagecreatefromjpeg($this->upload_dir.$this->image_name);
                break;

            case 'image/png' :
                $img = imagecreatefrompng($this->upload_dir.$this->image_name);
                break;
        }

        imagealphablending($icon, FALSE);
        imagesavealpha($icon, TRUE);
        imagecopyresampled($icon, $img, 0, 0, 0, 0, $this->thumb_width, $this->thumb_height, $this->image_width, $this->image_height);
        imagepng($icon, $this->upload_dir.$name);
        imagedestroy($img);
        imagedestroy($icon);
        if ($this->image_name !== $name) {
            unlink($this->upload_dir.$this->image_name);
        }
        return TRUE;
    }

    /**
     * Generates thumbnail.
     *
     * @param  string $file Image name
     * @return boolean      TRUE if oparation is successful
     */
    public function generateThumbnail($file = '') {
        if (!empty($file)) {
            $this->image_name = $file;
        }
        self::calcResizeParams();
        $thumb = imagecreateTRUEcolor($this->thumb_width, $this->thumb_height);
        switch($this->image_type) {
            case 'image/gif' :
                $img = imagecreatefromgif ($this->upload_dir.$this->image_name);
                imagecolortransparent($img);
                break;

            case 'image/jpeg':
                $img = imagecreatefromjpeg($this->upload_dir.$this->image_name);
                break;

            case 'image/png' :
                $img = imagecreatefrompng($this->upload_dir.$this->image_name);
                imagealphablending($thumb, FALSE);
                imagesavealpha($thumb, TRUE);
                break;
        }
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $this->thumb_width, $this->thumb_height, $this->image_width, $this->image_height);
        imagejpeg($thumb, $this->upload_dir.$this->image_name.'.jpg');
        imagedestroy($img);
        imagedestroy($thumb);
        return TRUE;
    }

    /**
     * Sets image parameters.
     *
     * @param  array $image Image parameters
     * @param  array $info  Imege mime type
     */
    public function setImage($image, $info) {
        #
        # Allowed only letters and numbers, spaces are replaced with a sign "_"
        #
        $this->image_name   = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $image['name']));
        $this->image_size   = $image['size'];
        $this->image_width  = $info[0];
        $this->image_height = $info[1];
        $this->image_tmp    = $image['tmp_name'];
        $this->image_type   = $info['mime'];
    }

    /**
     * Checks if the file is acceptable and uploads it to upload directory.
     *
     * @param  array $image   Image parameters
     * @throws Exception "File is not allowed or is corrupted"
     * @throws Exception "File is too large"
     * @return string|boolean Image name or FALSE
     */
    public function upload($image) {
       if ($image['error'] === '0') {
            $this->setImage($image, getimagesize($image['tmp_name']));
            if (!in_array($this->image_type, $this->allowed_types)) {
                throw new Exception('File is not allowed or is corrupted');
            }
            if ($this->image_size >= $this->max_size) {
                throw new Exception('File is too large');
            }
            if (is_uploaded_file($this->image_tmp)) {
                if (move_uploaded_file($this->image_tmp, $this->upload_dir.$this->image_name)) {
                    return $this->image_name;
                }
            }
        }
        return FALSE;
    }
}
