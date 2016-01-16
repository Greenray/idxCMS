<?php
/**
 * Processing content: articles and comments.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
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
