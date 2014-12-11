<?php
/**
 * @file      tools/captcha.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/tools/captcha.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

session_start();

/** @class CAPTCHA
 * Captcha.
 */
class CAPTCHA {

    /** Captcha images
     * @param array
     */
    private $image = ['captcha.png', 'captcha_color.png'];

    /** Captcha code length
     * @param integer
     */
    private $length;

    /** Captcha code
     * @param string
     */
    private $code;

    /** Random array key to select image for captcha: b&w or color
     * @param integer (0 or 1)
     */
    private $idx;

    /** Class initialization.
     * @param  string $code Captcha code
     * @return nothing
     */
    public function __construct($code) {
        $this->length = (int) round(mt_rand(5, 8));
        $this->code   = strip_tags(stripslashes($code));
        $this->idx    = (int) round(mt_rand(0, 1));
    }

    /** Create Captcha.
     * It takes one of two images (b$w or color), form code from 5...8 symbols.
     * Then split code into two parts.
     * So the captcha is different for every time.
     * @return nothing
     */
    public function create() {
        $code  = substr(md5($this->code), 0, $this->length);
        $image = imagecreatefrompng($this->image[$this->idx]);
        $color = imagecolorallocate($image, 255, 255, 255);
        $fh_code = substr($code, 0, 3);
        $sh_code = substr($code, 3);
        switch($this->length) {
            case 5:
                imagestring($image, 5, 20, 8, $fh_code, $color);
                imagestring($image, 5, 45, 2, $sh_code, $color);
                break;
            case 6:
                imagestring($image, 5, 17, 2, $fh_code, $color);
                imagestring($image, 5, 43, 8, $sh_code, $color);
                break;
            case 7:
                imagestring($image, 5, 15, 8, $fh_code, $color);
                imagestring($image, 5, 41, 2, $sh_code, $color);
                break;
            case 8:
                imagestring($image, 5, 11, 2, $fh_code, $color);
                imagestring($image, 5, 36, 8, $sh_code, $color);
                break;
        }
        imagepng($image);
        imagedestroy($image);
        $_SESSION['code-length'] = $this->length;   # It is for future validation
    }
}

if (!empty($_GET['code'])) {
    header("Content-type: image/png");
    $CAPTCHA = new CAPTCHA($_GET['code']);
    $CAPTCHA->create();
}