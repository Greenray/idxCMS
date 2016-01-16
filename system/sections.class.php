<?php
/**
 * Processing sections.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/sections.class.php
 * @package   Core
 */

class SECTIONS extends DBASE {

    /** Class initialization */
    protected function __construct() {}

    /**
     * Gets module sections data.
     *
     * @return array Sections which are allowed for current user
     */
    public function getSections() {
        if (empty($this->sections)) {
            $index = $this->getIndex($this->container);
            foreach ($index as $id => $section) {
                if (USER::checkAccess($section)) {
                    $this->sections[$id] = $section;
                }
            }
        }
        return $this->sections;
    }

    /**
     * Gets section`s data.
     *
     * @param  string $id Section name
     * @return array|boolean Section data or FALSE
     */
    public function getSection($id) {
        if (empty($this->sections[$id])) return FALSE;

        $this->section = $id;
        return $this->sections[$id];
    }

    /**
     * Shows all sections with their categories of the current module.
     *
     * @return array Data about all existing sections
     */
    public function showSections() {
        SYSTEM::set('pagename', SYSTEM::$modules[$this->module]['title'].' - '.__('Sections'));
        SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.__('Sections'));
        $result   = [];
        $sections = $this->sections;
        unset($sections['drafts']);

        foreach ($sections as $id => $section) {
            #
            # Get only allowed categories for user
            # Don't show sections with empty categories
            #
            $categories = $this->getCategories($id);
            if (!empty($categories)) {
                $result[$id] = $section;
                $result[$id]['desc'] = CMS::call('PARSER')->parseText($section['desc']);

                foreach ($categories as $key => $category) {
                    $result[$id]['categories'][$key] = $category;
                    $result[$id]['categories'][$key]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
                }
            }
        }
        return $result;
    }

    /**
     * Shows requested section with its categories.
     *
     * @param  string Section name
     * @return arrayboolean Section data or FALSE
     *
     * @todo Remove $category['last']
     */
    public function showSection($section) {
        $categories = $this->getCategories($section);
        if (!$categories) {
            return FALSE;
        }

        SYSTEM::set('pagename', $this->sections[$section]['title']);
        if (!empty($this->sections[$section]['desc']))
             SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.$this->sections[$section]['title'].' - '.$this->sections[$section]['desc']);
        else SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.$this->sections[$section]['title']);

        SYSTEM::setPageKeywords($this->sections[$section]['id']);

        $result = [];
        $result['title'] = $this->sections[$section]['title'];
        foreach($categories as $id => $category) {
            $category = $this->getCategory($id);
            $this->getContent($id);
            $category['desc']  = CMS::call('PARSER')->parseText($category['desc']);
            $category['items'] = sizeof($this->content);
            if (!empty($this->content)) {
                $last = end($this->content);
                $category['last_id']    = $last['id'];
                $category['last_title'] = $last['title'];
            }
            $result['categories'][] = $category;
        }
        return $result;
    }

    /**
     * Saves all sections.
     *
     * @param  array $sections Sections data
     * @throws Exception "Cannot save sections"
     */
    public function saveSections($sections) {
        $new = [];
        foreach ($sections as $key => $section) {
            $new[$section] = $this->sections[$section];
        }
        $this->sections = $new;
        if (!$this->saveIndex($this->container, $new)) {
            throw new Exception('Cannot save sections');
        }
    }

    /**
     * Saves section.
     * If parameter $id is not set, a new section will be created.
     * This function corrects website sitemap.
     *
     * @throws Exception "Invalid ID"          Empty ID or includes incorrect symbols
     * @throws Exception "Title is empty"
     * @throws Exception "Cannot save section" Cannot create directory for section or save section index file
     * @return boolean                         The result of operation
     */
    public function saveSection() {
        $id = OnlyLatin(FILTER::get('REQUEST', 'section'));
        if (!$id) {
            throw new Exception('Invalid ID');
        }
        $title = FILTER::get('REQUEST', 'title');
        if (!$title) {
            throw new Exception('Title is empty');
        }
        if (!is_dir($this->container.$id)) {
            if (!mkdir($this->container.$id, 0777)) {
                throw new Exception('Cannot save section');
            }
            if (!$this->saveIndex($this->container, [])) {
                rmdir($this->container.$id);
                throw new Exception('Cannot save section');
            }
        }

        $this->sections[$id]['id']     = $id;
        $this->sections[$id]['title']  = $title;
        $this->sections[$id]['desc']   = FILTER::get('REQUEST', 'desc');
        $this->sections[$id]['access'] = (int) (FILTER::get('REQUEST', 'access'));
        $this->sections[$id]['link']   = MODULE.$this->module.SECTION.$id;
        $this->sections[$id]['path']   = $this->container.$id.DS;

        if (!$this->saveIndex($this->container, $this->sections)) {
            DeleteTree($this->container.$id);
            throw new Exception('Cannot save section');
        }
        Sitemap();
    }

    /**
     * Removes section.
     * If parameter $id is not set, a new section will be created.
     * This function corrects website sitemap.
     *
     * @param  string    $id                     Section name
     * @throws Exception "Invalid ID"            - ID is invalid or is empty
     * @throws Exception "Cannot remove section"
     * @return boolean                           The result of operation
     */
    public function removeSection($id) {
        if (empty($this->sections[$id])) {
            throw new Exception('Invalid ID');
        }
        unset($this->sections[$id]);

        if (!$this->saveIndex($this->container, $this->sections) ||
            !DeleteTree($this->container.$id)) {
            throw new Exception('Cannot remove section');
        }
        Sitemap();
    }
}
