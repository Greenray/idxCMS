<?php
# idxCMS Flat Files Content Management Sysytem

/** Forum.
 * @file      system/forum.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Forum
 */

class FORUM extends CONTENT {

    public function __construct() {
        $this->module = 'forum';
        $this->container = FORUM;
    }

    public function getItem($id, $type = '', $parse = TRUE) {
        return parent::getItem($id, 'text', $parse);
    }

    public function saveTopic($id = '') {
        if (!USER::loggedIn()) {
            throw new Exception('You are not logged in!');
        }
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = trim(FILTER::get('REQUEST', 'text'));
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        if (empty($id)) {
            $id = $this->newId($this->content);
            if (mkdir($path.$id, 0777) === FALSE) {
                throw new Exception('Cannot save topic');
            }
            $this->content[$id]['id']       = (int) ($id);
            $this->content[$id]['author']   = USER::getUser('username');
            $this->content[$id]['nick']     = USER::getUser('nickname');
            $this->content[$id]['time']     = time();
            $this->content[$id]['ip']       = $_SERVER['REMOTE_ADDR'];
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $this->content[$id]['title']  = $title;
        $this->content[$id]['opened'] = (int) empty(FILTER::get('REQUEST', 'opened')) ? 1 : 0;
        $this->content[$id]['pinned'] = (int) FILTER::get('REQUEST', 'pinned');
        if (file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX) === FALSE) {
            throw new Exception('Cannot save topic');
        }
        parent::saveContent($this->content);
        Sitemap();
        return $id;
    }
}
