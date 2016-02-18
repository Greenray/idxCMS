<?php
/**
 * Forum.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/forum.class.php
 * @package   Forum
 */

class FORUM extends CONTENT {

    /** Class initialization */
    public function __construct() {
        parent::__construct();
        $this->module    = 'forum';
        $this->container = FORUM;
    }

    /**
     * Gets topic.
     *
     * @param  integer $id    Topic ID
     * @param  string  $type  Type of item: full text or description (default = '')
     * @param  boolean $parse Parse text? (default = TRUE)
     * @return array          Topic data
     */
    public function getItem($id, $type = '', $parse = TRUE) {
        return parent::getItem($id, 'text', $parse);
    }

    /**
     * Saves topic.
     * This function corrects website sitemap.
     *
     * @param  integer   $id                      Topic ID
     * @throws Exception "You are not logged in!" Title is empty or has wrong symbols
     * @throws Exception "Title is empty"         Title is empty or has wrong symbols
     * @throws Exception "Text is empty"
     * @throws Exception "Cannot save topic"      No access rights or cannot save index file
     * @return integer                            ID of the saved topic
     */
    public function saveTopic($id = '') {
        if (!USER::$logged_in) {
            throw new Exception('You are not logged in!');
        }

        $title = FILTER::get('REQUEST', 'title');
        if (!$title) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }

        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        if (empty($id)) {
            $id = $this->newId($this->content);

            if (!mkdir($path.$id, 0777)) {
                throw new Exception('Cannot save topic');
            }

            $this->content[$id]['id']       = $id;
            $this->content[$id]['author']   = USER::getUser('user');
            $this->content[$id]['nick']     = USER::getUser('nick');
            $this->content[$id]['time']     = time();
            $this->content[$id]['ip']       = $_SERVER['REMOTE_ADDR'];
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $this->content[$id]['title']  = $title;
        $this->content[$id]['opened'] = empty(FILTER::get('REQUEST', 'opened')) ? TRUE : FALSE;
        $this->content[$id]['pinned'] = FILTER::get('REQUEST', 'pinned');

        if (!file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX)) {
            throw new Exception('Cannot save topic');
        }
        parent::saveContent($this->content);

        Sitemap();
        return $id;
    }
}
