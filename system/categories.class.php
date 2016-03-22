<?php
/**
 * Processing categories.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.1 International
 * @file      system/categories.class.php
 * @package   Core
 */

class CATEGORIES extends SECTIONS {

    /** Class initialization */
    protected function __construct() {}

    /**
     * Gets all categories of the requested section.
     *
     * @param  string $section Section name
     * @return array|boolean   Section categories allowed for current user or FALSE
     */
    public function getCategories($section) {
        if (empty($this->sections[$section])) {
            return FALSE;
        }

        $this->section = $section;
        $categories = [];

        if (!empty($this->sections[$section]['categories'])) {
            foreach ($this->sections[$section]['categories'] as $id => $category) {
                if (USER::checkAccess($category)) {
                    $categories[$id] = $category;
                }
            }
        }
        return $categories;
    }

    /**
     * Gets requested category.
     *
     * @param  integer $id  Category ID
     * @return array|boolean Category data or FALSE
     */
    public function getCategory($id) {
        if (empty($this->sections[$this->section]['categories'][$id])) {
            return FALSE;
        }

        $this->category = $id;
        return $this->sections[$this->section]['categories'][$id];
    }

    /**
     * Saves all categories from requested section.
     *
     * @param  string    $section    Section name
     * @param  array     $categories Categories data
     * @throws Exception "Cannot save categories"
     */
    public function saveCategories($section, $categories) {
        $this->sections[$section]['categories'] = $categories;

        if (!$this->saveIndex($this->container, $this->sections)) {
            throw new Exception('Cannot save categories');
        }
    }

    /**
     * Saves category, if parameter $id is not set, a new category will be created.
     * This function corrects website sitemap.
     *
     * @throws Exception "Title is empty"
     * @throws Exception "Cannot create category" - Cannot create category directory or save index file
     * @return boolean                            The result
     */
    public function saveCategory() {
        $title = FILTER::get('REQUEST', 'title');
        if (!$title) {
            throw new Exception('Title is empty');
        }

        $id = FILTER::get('REQUEST', 'category');
        if (empty($id)) {
            #
            # Create new directory with empty index
            #
            $id   = $this->newId($this->sections[$this->section]['categories']);
            $item = $this->sections[$this->section]['path'].$id;

            if (is_dir($item)) {
                if (!DeleteTree($item)) {
                    throw new Exception('Cannot save category');
                }
            }
            if (!mkdir($item, 0777) || !$this->saveIndex($item.DS, [])) {
                throw new Exception('Cannot save category');
            }
        }
        #
        # Access level of the category should be more or is equal to access level of the section
        #
        $access = FILTER::get('REQUEST', 'access');
        $section_access = $this->sections[$this->section]['access'];

        $this->sections[$this->section]['categories'][$id]['id']     = $id;
        $this->sections[$this->section]['categories'][$id]['title']  = $title;
        $this->sections[$this->section]['categories'][$id]['desc']   = FILTER::get('REQUEST', 'desc');
        $this->sections[$this->section]['categories'][$id]['access'] = ($section_access >= $access) ? $section_access : $access;
        $this->sections[$this->section]['categories'][$id]['link']   = $this->sections[$this->section]['link'].CATEGORY.$id;
        $this->sections[$this->section]['categories'][$id]['path']   = $this->sections[$this->section]['path'].$id.DS;
        #
        # If icon is not set an empty image will be shown
        #
        $this->setIcon($this->sections[$this->section]['categories'][$id]['path'], FILTER::get('REQUEST', 'icon'));

        if (!$this->saveIndex($this->container, $this->sections)) {
            throw new Exception('Cannot save category');
        }

        Sitemap();
    }

    /**
     * Moves category into another section.
     *
     * @param  integer $id     ID of the category which will be moved
     * @param  string  $source Name of the source section
     * @param  string  $dest   Name of the destination section
     * @return integer|boolean ID of the new category or FALSE
     */
    public function moveCategory($id, $source, $dest) {
        if (empty($this->sections[$source])) return FALSE;
        if (empty($this->sections[$source]['categories'][$id])) return FALSE;

        $new = $this->newId($this->sections[$dest]['categories']);

        $this->copyTree($this->sections[$source]['path'].$id, $this->sections[$dest]['path'].$new);
        DeleteTree($this->sections[$source]['path'].$id);

        $this->sections[$dest]['categories'][$new] = $this->sections[$source]['categories'][$id];
        $this->sections[$dest]['categories'][$new]['link'] = $this->sections[$dest]['link'].CATEGORY.$new;
        $this->sections[$dest]['categories'][$new]['path'] = $this->sections[$dest]['path'].$new.DS;

        unset($this->sections[$source]['categories'][$id]);

        $this->saveIndex($this->container, $this->sections);
        return $new;
    }

    /**
     * Removes category.
     * This function corrects website sitemap.
     *
     * @param  integer   $id Category ID
     * @throws Exception "Cannot remove category" - Cannot remove category directory tree or save index file
     * @return boolean   The result
     */
    public function removeCategory($id) {
        unset($this->sections[$this->section]['categories'][$id]);

        if (!DeleteTree($this->sections[$this->section]['path'].$id) ||
            !$this->saveIndex($this->container, $this->sections)) {
            throw new Exception('Cannot remove category');
        }

        Sitemap();
    }

    /**
     * Sets icon for category.
     *
     * @param string $path Path to destination directory
     * @param array  $icon Image data
     */
    protected function setIcon($path, $icon) {
        if (empty($icon['name']) && file_exists($path.'icon.png')) {
            return;
        }

        $IMAGE = new IMAGE($path, '', 35, 35);
        if (!$IMAGE->upload($icon)) {
            #
            # Set the default transparent icon
            #
            copy(IMAGES.'icon.png', $path.'tmp.png');
            $IMAGE->setImage([
                'name'     => 'tmp.png',
                'size'     => 149,
                'tmp_name' => ''],
                [35, 35, 'mime' => 'image/png']
            );

        } else $IMAGE->generateIcon();
    }
}
