<?php
/**
 * Processing content: articles and comments.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.2
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/posts.class.php
 * @package   Posts
 */

class POSTS extends CONTENT {

    /** Class initialization */
    function __construct() {
        $this->module    = 'posts';
        $this->container = POSTS;
    }
}
