<?php
# idxCMS Flat Files Content Management Sysytem

/** Processing content: articles and comments.
 * 
 * @file      system/posts.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Posts
 */

if (!defined('idxCMS')) die();

/** Class POSTS - news and articles */
class POSTS extends CONTENT {

    /** Class initialization. */
    function __construct() {
        $this->module = 'posts';
        $this->container = POSTS;
    }
}
