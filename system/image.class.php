<?php
/** Uploads and resize images.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/image.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

class IMAGE {

    /** Allowed image types.
     * @var array
     */
    private $allowed_types = ['image/gif', 'image/jpeg', 'image/png'];

    /** The height of the uploading image.
     * @var integer
     */
    private $image_height;

    /** The name of the uploading image.
     * @var string
     */
    private $image_name;

    /** The size of the upoading image.
     * @var integer
     */
    private $image_size;

    /** Name of the temporary image.
     * @var string
     */
    private $image_tmp;

    /** The width of the uploading image.
     * @var integer
     */
    private $image_width;

    /** Image type.
     * @param string
     */
    private $image_type;

    /** Max size for uploading files.
     * @var integer
     */
    private $max_size;

    /** The height of the thumbnail.
     * @var integer
     */
    private $thumb_height;

    /** The width of the thumbnail.
     * @var integer
     */
    private $thumb_width;

    /** Directory for uploaded files.
     * @var string
     */
    private $upload_dir;

    /** Class initialization.
     *
     * @param  string  $upload_dir   Name of the upload directory
     * @param  integer $max_size     Image max size
     * @param  integer $thumb_width  Thumbnail width
     * @param  integer $thumb_height Thumbnal height
     * @return void
     */
    public function __construct($upload_dir, $max_size = '', $thumb_width = '', $thumb_height = '') {
        $this->upload_dir   = $upload_dir;
        $this->max_size     = empty($max_size)     ? (int) CONFIG::getValue('main', 'image-max-size') : (int) $max_size;
        $this->thumb_width  = empty($thumb_width)  ? (int) CONFIG::getValue('main', 'thumb-width')    : (int) $thumb_width;
        $this->thumb_height = empty($thumb_height) ? (int) CONFIG::getValue('main', 'thumb-height')   : (int) $thumb_height;
    }

    /** Set image parameters.
     *
     * @param  array $image Image parameters
     * @param  array $info  Imege mime type
     * @return void
     */
    public function setImage($image, $info) {
        # Allowed only letters and numbers, spaces are replaced with a sign "_",
        $this->image_name   = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $image['name']));
        $this->image_size   = $image['size'];
        $this->image_width  = $info[0];
        $this->image_height = $info[1];
        $this->image_tmp    = $image['tmp_name'];
        $this->image_type   = $info['mime'];
    }

    /** Check if image is valid.
     *
     * @return boolean   TRUE if image is valid
     * @throws Exception Your file is not allowed or is corrupted
     * @throws Exception File is too large
     */
    private function checkImage() {
        if (!in_array($this->image_type, $this->allowed_types)) {
            throw new Exception('Your file is not allowed or is corrupted');
        }
        if ($this->image_size >= $this->max_size) {
            throw new Exception('File is too large');
        }
        return TRUE;
    }

    /** Checks if the file is acceptable and uploads it to upload directory.
     *
     * @param  array $image   Image parameters
     * @return string|boolean Image name or FALSE
     */
    public function upload($image) {
       if ($image['error'] === '0') {
            $this->setImage($image, getimagesize($image['tmp_name']));
            if ($this->checkImage()) {
                if (is_uploaded_file($this->image_tmp)) {
                    if (move_uploaded_file($this->image_tmp, $this->upload_dir.$this->image_name)) {
                        return $this->image_name;
                    }
                }
            }
        }
        return FALSE;
    }

    /** Calculate parameters to resize image.
     * @return void
     */
    private function calcResizeParams() {
        $ratio = $this->image_width / $this->image_height;
        if (($this->thumb_width / $this->thumb_height) > $ratio) {
             $this->thumb_width  = $this->thumb_height * $ratio;
        } else {
            $this->thumb_height = $this->thumb_width / $ratio;
        }
    }

    /** Generate thumbnail.
     *
     * @param  string $file Image name
     * @return boolean      TRUE if oparation is successful
     */
    public function generateThumbnail($file = '') {
        if (!empty($file)) {
            $this->image_name = $file;
        }
        self::calcResizeParams();
        $thumb = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
        switch($this->image_type) {
            case 'image/gif' :
                $img = imagecreatefromgif($this->upload_dir.$this->image_name);
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

    /** Generate icon.
     *
     * @param  string $name Icon name
     * @return boolean      TRUE if oparation is successful
     */
    public function generateIcon($name = '') {
        if (!empty($name)) {
            $name = $name.'.png';
        } else {
            $name = 'icon.png';
        }
        self::calcResizeParams();
        $icon = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
        switch($this->image_type) {
            case 'image/gif' :
                $img = imagecreatefromgif($this->upload_dir.$this->image_name);
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
}
