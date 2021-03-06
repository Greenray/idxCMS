<?php
/**
 * Captcha.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      tools/captcha.php
 * @package   Tools
 * @overview  Captcha.
 */
session_start();

class CAPTCHA {

    /**
     * Creates Captcha.
     *
     * It takes one of two images (b$w or color), form code from 5...8 symbols, then split code into two parts.
     * So the captcha is different for every time.
     *
     * @param  string $code Alpha-numeric code for captcha
     */
    public function CAPTCHA($code) {
        $images = ['captcha.png', 'captcha_color.png'];
        $length = round(mt_rand(5, 8));
        $code   = substr(md5($code), 0, $length);
        $image  = imagecreatefrompng($images[round(mt_rand(0, 1))]);
        $color  = imagecolorallocate($image, 255, 255, 255);
        $f_code = substr($code, 0, 3);
        $s_code = substr($code, 3);

        switch($length) {
            case 5:
                imagestring($image, 5, 20, 8, $f_code, $color);
                imagestring($image, 5, 45, 2, $s_code, $color);
                break;

            case 6:
                imagestring($image, 5, 17, 2, $f_code, $color);
                imagestring($image, 5, 43, 8, $s_code, $color);
                break;

            case 7:
                imagestring($image, 5, 15, 8, $f_code, $color);
                imagestring($image, 5, 41, 2, $s_code, $color);
                break;

            case 8:
                imagestring($image, 5, 11, 2, $f_code, $color);
                imagestring($image, 5, 36, 8, $s_code, $color);
                break;
        }
        imagepng($image);
        imagedestroy($image);
        $_SESSION['code_length'] = $length;   # It is for validation
    }
}
#
# Show Captcha
#
if (!empty($_GET['code'])) {
    header("Content-type: image/png");
    $CAPTCHA = new CAPTCHA($_GET['code']);
}
