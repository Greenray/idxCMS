<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com

# Uploads files
class UPLOADER {

    private $upload_dir;
    private $source_file;
    private $target_file;
    private $max_size;
    private $file_size;
    private $file_tmp;
    private $file_type;
    private $allowed_types = array(
                                'application/zip',
                                'application/rar',
                                'application/arj',
                                'application/tar',
                                'application/x-gzip',
                                'application/bzip2',
                                'application/pdf',
                                'audio/mp3'
    );

    public function __construct($upload_dir, $max_size = '') {
        $this->upload_dir = $upload_dir;
        $this->max_size   = empty($max_size) ? CONFIG::getValue('main', 'file-max-size') : $max_size;
    }

    # Set file parameters
    private function setFile($file) {
        # Letters and numbers are settled only, spaces are replaced with a sign "_",
        $this->file_name = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $file['name']));
        $this->file_size = $file['size'];
        $this->file_tmp  = $file['tmp_name'];
        $this->file_type = $file['type'];
    }

    private function checkFile() {
        if (!in_array($this->file_type, $this->allowed_types))
            throw new Exception('Your file is not allowed or is corrupted');

        if ($this->file_size >= $this->max_size)
            throw new Exception('File is too large');

        return TRUE;
    }

    # Checks if the file is acceptable and uploads it to upload directory
    public function upload($file) {
        if ($file['error'] === '0') {
            $this->setFile($file);
            $this->checkFile();
            if (is_uploaded_file($this->file_tmp)) {
                if (move_uploaded_file($this->file_tmp, $this->upload_dir.$this->file_name))
                    return array($this->file_name, $this->file_size);

            }
        }
        throw new Exception('Error of file uploading');
    }
}

# Uploads and resize images
class IMAGE {

    private $upload_dir;
    private $max_size;
    private $thumb_width;
    private $thumb_height;
    private $image_name;
    private $image_size;
    private $image_width;
    private $image_height;
    private $image_tmp;
    private $image_type;
    private $allowed_types = array(
                                'image/gif',
                                'image/jpeg',
                                'image/png',
    );

    public function __construct($upload_dir, $max_size = '', $thumb_width = '', $thumb_height = '') {
        $this->upload_dir   = $upload_dir;
        $this->max_size     = empty($max_size)     ? (int) CONFIG::getValue('main', 'image-max-size') : (int) $max_size;
        $this->thumb_width  = empty($thumb_width)  ? (int) CONFIG::getValue('main', 'thumb-width')    : (int) $thumb_width;
        $this->thumb_height = empty($thumb_height) ? (int) CONFIG::getValue('main', 'thumb-height')   : (int) $thumb_height;
    }

    # Set image parameters
    public function setImage($image, $info) {
        # Allowed only letters and numbers, spaces are replaced with a sign "_",
        $this->image_name   = preg_replace('[^a-z0-9._]', '', str_replace('%20', '_', $image['name']));
        $this->image_size   = $image['size'];
        $this->image_width  = $info[0];
        $this->image_height = $info[1];
        $this->image_tmp    = $image['tmp_name'];
        $this->image_type   = $info['mime'];
    }

    private function checkImage() {
        if (!in_array($this->image_type, $this->allowed_types))
            throw new Exception('Your file is not allowed or is corrupted');

        if ($this->image_size >= $this->max_size)
            throw new Exception('File is too large');

        return TRUE;
    }

    # Checks if the file is acceptable and uploads it to upload directory
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

    private function calcResizeParams() {
        $ratio = $this->image_width / $this->image_height;
        if (($this->thumb_width / $this->thumb_height) > $ratio)
             $this->thumb_width  = $this->thumb_height * $ratio;
        else $this->thumb_height = $this->thumb_width / $ratio;
    }

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

    public function generateIcon($name = '') {
        if (!empty($name))
             $name = $name.'.png';
        else $name = 'icon.png';
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
        if ($this->image_name !== $name) unlink($this->upload_dir.$this->image_name);
        return TRUE;
    }
}
?>