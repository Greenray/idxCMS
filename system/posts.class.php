<?php
/** Processing content: articles and comments.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/posts.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Posts
 */

if (!defined('idxCMS')) die();

class POSTS extends CONTENT {

    /** Class initialization. */
    function __construct() {
        $this->module    = 'posts';
        $this->container = POSTS;
    }
}
