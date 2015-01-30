<?php
# idxCMS Flat Files Content Management Sysytem

/** Processing catalogs (ex. files, links, etc...).
 * @file      system/catalogs.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Catalogs
 */

class CATALOGS extends CONTENT {

    /** Class initialization. */
    public function __construct() {
        $this->module = 'catalogs';
        $this->container = CATALOGS;
    }

    public function saveItem($id) {
        $title = FILTER::get('REQUEST', 'title');
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        $file = FILTER::get('REQUEST', 'file');
        if (empty($id)) {
            if (($this->section !== 'links') && empty($file)) {
                throw new Exception('Nothing to upload');
            }
            $id = $this->newId($this->content);
            if (is_dir($path.$id)) {
                rmdir($path.$id);
            }
            if (mkdir($path.$id, 0777) === FALSE) {
                throw new Exception('Cannot save file');
            }
            $this->content[$id]['id']       = (int)$id;
            $this->content[$id]['author']   = USER::getUser('username');
            $this->content[$id]['nick']     = USER::getUser('nickname');
            $this->content[$id]['time']     = time();
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
            if ($this->section !== 'links') {
                $this->content[$id]['downloads'] = 0;
            } else {
                $this->content[$id]['clicks'] = 0;
            }
        }
        if (!empty($file['name'])) {
            try {
                $uploaded = self::uploadFile($id, $file);
            } catch (Exception $error) {
                throw new Exception($error->getMessage());
            }
            $path_parts = pathinfo($uploaded[0]);
            if ($path_parts['extension'] === 'mp3') {
                $this->content[$id]['song'] = $uploaded[0];
            } else {
                $this->content[$id]['file'] = $uploaded[0];
            }
            $this->content[$id]['size'] = (int) $uploaded[1];
        } else {
            if ($this->section === 'links') {
                $this->content[$id]['site'] = FILTER::get('REQUEST', 'site');
            }
        }
        $this->content[$id]['title']     = $title;
        $this->content[$id]['keywords']  = FILTER::get('REQUEST', 'keywords');
        $this->content[$id]['copyright'] = FILTER::get('REQUEST', 'copyright');
        $this->content[$id]['opened']    = (bool) FILTER::get('REQUEST', 'opened');
        $desc = FILTER::get('REQUEST', 'desc');
        if (empty($desc)) {
            $desc = CutText($text, CONFIG::getValue('catalogs', 'description-length'));
        }
        if ((file_put_contents($path.$id.DS.$this->desc, $desc, LOCK_EX) === FALSE) ||
            (file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX) === FALSE)) {
            throw new Exception('Cannot save file');
        }
        parent::saveContent($this->content);
        Sitemap();
    }

    protected function uploadFile($id, $file) {
        if (empty($file['name'])) {
            throw new Exception('Nothing to upload');
        }
        $UPLOAD = new UPLOADER($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        return $UPLOAD->upload($file);
    }

    public function getComment($id, $page) {
        $comment = parent::getComment($id, $page);
        if (!empty($comment['rateid'])) {
            $comment['rateid'] = $this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
        }
        return $comment;
    }
}
