<?php
/**
 * Processing items.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/items.class.php
 * @package   Core
 */

class ITEMS extends CATEGORIES {

    /** Class initialization */
    protected function __construct() {}

    /**
     * Gets post or topic.
     *
     * @param  integer $id    Item ID
     * @param  string  $type  Type of item: full text or description (default = '')
     * @param  boolean $parse Parse text? (default = TRUE)
     * @return array|boolean  Item data or FALSE
     */
    public function getItem($id, $type = '', $parse = TRUE) {
        if (empty($this->content[$id])) {
            return FALSE;
        }

        $item = $this->content[$id];
        $path = $this->sections[$this->section]['categories'][$this->category]['path'].$id.DS;
        $item['link'] = $this->sections[$this->section]['categories'][$this->category]['link'].ITEM.$id;

        if (!empty($type)) {
            switch ($type) {

                case 'desc':
                    $item['desc'] = file_get_contents($path.$this->desc);
                    break;

                case 'text':
                    $item['text'] = file_get_contents($path.$this->text);
                    break;

                case 'full':
                    $item['desc'] = file_get_contents($path.$this->desc);
                    $item['text'] = file_get_contents($path.$this->text);
                    break;
            }

            if ($parse) {
                if (!empty($item['desc'])) {
                    $item['desc'] = CMS::call('PARSER')->parseText($item['desc'], $path);
                }
                if (!empty($item['text'])) {
                    $item['text'] = CMS::call('PARSER')->parseText($item['text'], $path);
                    if (USER::$root) {
                        $item['admin'] = TRUE;
                        if ($item['opened']) {
                            $item['command'] = __('Close');
                            $item['action']  = 'close';
                        } else {
                            $item['command'] = __('Open');
                            $item['action']  = 'open';
                        }
                    }
                }
            }

            $item['section']  = $this->section;
            $item['category'] = $this->category;
            $item['category_title'] = $this->sections[$this->section]['categories'][$this->category]['title'];
            $item['date'] = FormatTime('d F Y', $item['time']).' '.__('year');

            if (CONFIG::getValue('enabled', 'rate')) {
                $item['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$id;
                $item['rate'] = ShowRate($item['rateid']);
            }
        }
        return $item;
    }

    /**
     * Saves post, catalogs item or topic.
     * This function corrects website sitemap.
     *
     * @param  integer    $id                 Item ID
     * @throws Exception "Title is empty"     Title is empty or has wrong symbols
     * @throws Exception "Text is empty"
     * @throws Exception "Cannot create item" No access rights
     * @return integer                        ID of the saved item
     */
    public function saveItem($id) {
        $title = FILTER::get('REQUEST', 'title');
        if (!$title) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path  = $this->sections[$this->section]['categories'][$this->category]['path'];
        $item  = $path.$id;
        if (empty($id)) {
            $id   = $this->newId($this->content);
            $item = $path.$id;
            if (is_dir($item)) {
                DeleteTree($item);
            }
            if (!mkdir($path.$id, 0777)) {
                throw new Exception('Cannot create'.' '.$item);
            }
            $this->content[$id]['id']       = (int) $id;
            $this->content[$id]['author']   = USER::getUser('user');
            $this->content[$id]['nick']     = USER::getUser('nick');
            $this->content[$id]['time']     = time();
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $file = FILTER::get('REQUEST', 'file');
        if (!empty($file)) {
            try {
                $uploaded = CMS::call('CATALOGS')->uploadFile($id, $file);
            } catch (Exception $error) {
                throw new Exception($error->getMessage());
            }
            $this->content[$id]['file']      = $uploaded[0];
            $this->content[$id]['size']      = (int) $uploaded[1];
            $this->content[$id]['downloads'] = 0;
            $this->content[$id]['copyright'] = FILTER::get('REQUEST', 'copyright');
        } else {
            $section = FILTER::get('REQUEST', 'section');
            if ($section === 'links') {
                $this->content[$id]['site'] = FILTER::get('REQUEST', 'site');
                $this->content[$id]['copyright'] = FILTER::get('REQUEST', 'copyright');
            }
        }
        $this->content[$id]['title']    = $title;
        $this->content[$id]['keywords'] = FILTER::get('REQUEST', 'keywords');
        $this->content[$id]['opened']   = FILTER::get('REQUEST', 'opened');
        $desc = FILTER::get('REQUEST', 'desc');
        if (empty($desc)) {
            $desc = $this->cutText($text, CONFIG::getValue('gallery', 'description_length'));
        }
        if (!file_put_contents($item.DS.$this->desc, $desc, LOCK_EX) ||
            !file_put_contents($item.DS.$this->text, $text, LOCK_EX)) {
            throw new Exception('Cannot save file');
        }
        $this->saveContent($this->content);
        Sitemap();
        return $id;
    }

    /**
     * Moves iten to another section/category or category.
     *
     * @param  integer   $id       Item ID
     * @param  string    $section  Section name
     * @param  integer   $category Category ID
     * @throws Exception "Cannot move item"
     * @return integer             ID of the new item
     */
    public function moveItem($id, $section, $category) {
        $item = $this->content[$id];
        $old_section   = $this->section;
        $old_category  = $this->category;
        $this->section = $section;
        $this->getContent($category);
        $new    = $this->newId($this->content);
        $source = $this->sections[$old_section]['categories'][$old_category]['path'];
        $dest   = $this->sections[$section]['categories'][$category]['path'];
        if (!$this->copyTree($source.$id, $dest.$new)) {
            rmdir($dest.$new);
            throw new Exception('Cannot move item');
        }

        $this->content[$new] = $item;
        $this->content[$new]['id'] = $new;
        $this->saveContent($this->content);
        $this->section = $old_section;
        $this->getContent($old_category);
        self::removeItem($item['id']);
        return $new;
    }

    /**
     * Removes item from database.
     *
     * @param  integer   $id Item ID
     * @throws Exception "Invalid ID"
     * @throws Exception "Cannot remove item"
     * @return boolean   The result of operation
     */
    public function removeItem($id) {
        if (empty($this->content[$id])) {
            throw new Exception('Invalid ID');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];

        unset($this->content[$id]);
        return (DeleteTree($path.$id) && $this->saveIndex($path, $this->content)) ? Sitemap() : FALSE;
    }

    /**
     * Gets list of the latest items from specified sections.
     * The number of the latest items specified in configuration.
     *
     * @param  array $sections List of sections (Default : '')
     * @return array           List of latest items
     */
    public function getSectionsLastItems($sections = '') {
        $result = [];
        if (empty($sections)) {
            if (empty($this->sections))
                 $sections = $this->getSections();
            else $sections = $this->sections;
        }
        foreach($sections as $id => $section) {
            $this->getCategories($id);
            $last = $this->getStat('time');         # Get last items from section categories
            foreach ($last as $key => $time) {
                $result[$time] = $id.'.'.$key;      # Value is section.category.post
            }
        }
        return $result;
    }
}
