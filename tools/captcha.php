<?php
session_start();

/**
 * @package    idxCMS
 * @subpackage TOOLS
 * @file       tools/captcha.php
 * @version    2.3
 * @author     Victor Nabatov <greenray.spb@gmail.com>\n
 * @license    Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *             http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright  (c) 2011 - 2014 Victor Nabatov\n
 * @see        https://github.com/Greenray/idxCMS/tools/captcha.php
 */

/** Class CAPTCHA - captcha */
class CAPTCHA {

    /** Captcha images
     * @var array
     */
    private $image = array (0 => 'captcha.png', 1 => 'captcha_color.png');

    /** Captcha code length
     * @var integer
     */
    private $length;

    /** Captcha code
     * @var string
     */
    private $code;

    /** Random array key to select image for captcha: b&w or color
     * @var integer - 0 or 1
     */
    private $idx;

    /** Class initialization */
    public function __construct($code) {
        $this->length = (int) round(mt_rand(5, 8));
        $this->code   = strip_tags(stripslashes($code));
        $this->idx    = (int) round(mt_rand(0, 1));
    }

    /** Create Captcha */
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
        $_SESSION['code-length'] = $this->length;
    }
}

if (!empty($_GET['code'])) {
    header("Content-type: image/png");
    $CAPTCHA = new CAPTCHA($_GET['code']);
    $CAPTCHA->create();
}
