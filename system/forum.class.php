<?php
# idxCMS Flat Files Content Management Sysytem

/** Forum.
 *
 * @file      system/forum.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Forum
 */

class FORUM extends CONTENT {

    /** Class initialization. */
    public function __construct() {
        $this->module = 'forum';
        $this->container = FORUM;
    }

    /** Gets topic.
     * @param  integer $id    Topic ID
     * @param  string  $type  Type of item: full text or description (default = '')
     * @param  boolean $parse Parse text? (default = TRUE)
     * @return array          Topic data
     */
    public function getItem($id, $type = '', $parse = TRUE) {
        return parent::getItem($id, 'text', $parse);
    }

    /** Saves topic.
     * This function corrects website sitemap.
     * @param  integer $  id                      Topic ID
     * @throws Exception 'You are not logged in!' - Title is empty or has wrong symbols
     * @throws Exception 'Title is empty'         - Title is empty or has wrong symbols
     * @throws Exception 'Text is empty'          - Text is empty
     * @throws Exception 'Cannot save topic'      - No access rights or cannot save index file
     * @return integer                            ID of the saved topic
     */
    public function saveTopic($id = '') {
        if (!USER::loggedIn()) {
            throw new Exception('You are not logged in!');
        }
        $title = FILTER::get('REQUEST', 'title');
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
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
