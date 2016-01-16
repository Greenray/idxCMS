<?php
/**
 * Processing content: articles, topics, comments, replies, gallery and catalogs items.
 * The structure of the posts database:
 * <pre>
 * content                 - The main directory of the website content
 *     - posts             - The main directory of posts
 *       index             - The index file of the module "Posts"
 *       -- articles       - Section of posts
 *          --- 1          - Directory fo category
 *              icon.png   - icon for category
 *              index      - Index file for category and it content
 *              ---- 1     - Directory for article
 *                   index - Comments for article
 *                   desc  - Short description of the article
 *                   text  - Full text
 *                   rate  - Comments rates
 * </pre>
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/content.class.php
 * @package   Core
 */

class CONTENT extends COMMENTS {

    /** Class initialization */
    protected function __construct() {}

    /**
     * Sets the value of a field.
     *
     * @param integer $id    Item ID
     * @param string  $field Field name
     * @param mixed   $value Field value
     */
    public function setValue($id, $field, $value) {
        $this->content[$id][$field] = $value;
        return $this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'], $this->content);
    }

    /**
     * Gets content from the requested category.
     *
     * @param  integer $category Category ID
     * @return array|boolean     Category content or empty array
     */
    public function getContent($category) {
        if (empty($this->sections[$this->section]['categories'][$category])) {
            return [];
        }

        $this->category = $category;
        $this->content  = $this->getIndex($this->sections[$this->section]['categories'][$category]['path']);
        return $this->content;
    }


    /**
     * Saves content.
     *
     * @param  array     $content Content to save
     * @throws Exception 'Cannot save content'
     */
    public function saveContent($content) {
        if (!$this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'], $content)) {
            throw new Exception('Cannot save content');
        }
    }

    /**
     * Increments one of counts: views, downloads and clicks.
     *
     * @param  integer $id    Item ID
     * @param  string  $field Fieldname
     * @return boolean        The result of operation
     */
    public function incCount($id, $field) {
        if (empty($this->content[$id])) {
            return FALSE;
        }
        $this->content[$id][$field]++;
        return $this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'], $this->content);
    }

    /**
     * Gets content of the parameter.
    *
    * @param  mixed $param Parameter to search
    * @return array        Content according $param
    */
    public function getStat($param) {
        $result = [];
        if (empty($this->sections[$this->section]['categories'])) {
            return $result;
        }
        foreach ($this->sections[$this->section]['categories'] as $category => $data) {
            self::getContent($category);
            foreach ($this->content as $key => $item) {
                $result[$category.'.'.$key] = $item[$param];
            }
        }
        return $result;
    }

    /**
     * Gets the last items from category.
     * The number of the latest items specified in configuration.
     *
     * @param  string $format The parameter for formatting posts time
     * @return array          List of latest items
     */
    public function getCategoryLastItems($format) {
        $items  = array_flip($this->getStat('time'));
        krsort($items);

        $result = [];
        if (!empty($items)) {
            $items = array_slice($items, 0, CONFIG::getValue('main', 'last'), TRUE);
            foreach($items as $key => $data) {
                $id = explode('.', $data);
                self::getContent($id[0]);
                $item = $this->getItem($id[1]);
                $item['date'] = FormatTime($format, $item['time']);
                $result['items'][] = $item;
            }
        }
        return $result;
    }

    /**
     * Gets list of the latest items (posts, catalog's items? etc.).
     * The number of the latest items specified in configuration.
     *
     * @param  array $items Full list of items
     * @return array        List of latest items
     */
    public function getLastItems($items) {
        krsort($items);
        $items  = array_slice($items, 0, (int) CONFIG::getValue('main', 'last'), TRUE);
        $result = [];
        foreach ($items as $key => $data) {
            $parts = explode('.', $data);
            $this->getCategories($parts[0]);
            self::getContent($parts[1]);
            $item = $this->getItem($parts[2]);
            $item['date']      = FormatTime('d F Y', $item['time']);
            $item['section']   = $parts[0];
            $item['category']  = $parts[1];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * Truncates a text to a predetermined value.
     *
     * @param  string  $text   Text to truncate
     * @param  integer $length New length of text
     * @return string          Truncated string
     */
    protected function cutText($text, $length) {
        if ((mb_strlen($text, 'UTF-8') - 1) < (int) $length) {
            return $text;
        }
        if (mb_strpos($text, '.', $length)) {
            return mb_substr($text, 0, $length, 'UTF-8').'...';
        }
    }

    /**
     * Recursively copy a directory and its contents.
     * This function is recursive.
     *
     * @param  string  $source Sourse directory
     * @param  string  $dest   Destination directory
     * @return boolean        The result of operation
     */
    private function copyTree($source, $dest) {
        if (is_file($source)) {
            return copy($source, $dest);
        }
        if (!is_dir($dest)) {
            mkdir($dest, 0777);
            chmod($dest, 0777);
        }
        $dir = dir($source);
        while (($element = $dir->read()) !== FALSE) {
            if (($element == '.') || ($element == '..')) {
                continue;
            }
            $this->copyTree($source.DS.$element, $dest.DS.$element);
        }
        $dir->close();
        return TRUE;
    }
}
