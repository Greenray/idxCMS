<?php
# idxCMS Flat Files Content Management Sysytem

/** Processing content: articles, topics, comments and replies.
 * @file      system/content.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Core
 */

class CONTENT extends INDEX {

    /** Current module which use this class.
     * @var string
     */
    protected $module = '';

    /** The name of current module.
     * @var string
     */
    protected $container = '';

    /** Sections of the content of the carrent module.
     * @var array
     */
    protected $sections = array();

    /** ID of the current section.
     * @var string
     */
    protected $section = '';

    /** ID of the current category.
     * @var string
     */
    protected $category = '';

    /** Coontent of the current category.
     * @var array
     */
    protected $content = [];

    /** Filename of the item with full text.
     * @var string
     */
    protected $text = 'text';

    /** Filename of the item with short description.
     * @var string
     */
    protected $desc = 'desc';

    /** ID of the article, topic, image and so on.
     * @var integer
     */
    protected $item;

    /** Comments for article, topic, image and so on.
     * @var array
     */
    protected $comments = [];

    /** Get module sections data.
     * @return array Sections allowed for current user
     */
    public function getSections() {
        if (empty($this->sections)) {
            $index = self::getIndex($this->container);
            foreach ($index as $id => $section) {
                if (USER::checkAccess($section)) {
                    $this->sections[$id] = $section;
                }
            }
        }
        return $this->sections;
    }

    /** Get section`s data.
     * @param  string $id Section ID
     * @return array      Section data
     */
    public function getSection($id) {
        if (empty($this->sections[$id])) {
            return FALSE;
        }
        $this->section = $id;
        return $this->sections[$id];
    }

    /** Show all sections with their categories of the current module.
     * @return array
     */
    public function showSections() {
        SYSTEM::set('pagename', SYSTEM::$modules[$this->module]['title'].' - '.__('Sections'));
        SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.__('Sections'));
        $result   = [];
        $sections = $this->sections;
        if (isset($sections['drafts'])) {
            unset($sections['drafts']);
        }
        foreach ($sections as $id => $section) {
            # Get only allowed categories for user
            # Don't show sections with empty categories
            $categories = self::getCategories($id);
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

    /** Show requested section with its categories.
     * @return array
     */
    public function showSection($section) {
        $categories = self::getCategories($section);
        if ($categories === FALSE) {
            return FALSE;
        }
        SYSTEM::set('pagename', $this->sections[$section]['title']);
        if (!empty($this->sections[$section]['desc'])) {
            SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.$this->sections[$section]['title'].' - '.$this->sections[$section]['desc']);
        } else {
            SYSTEM::setPageDescription(SYSTEM::$modules[$this->module]['title'].' - '.$this->sections[$section]['title']);
        }
        SYSTEM::setPageKeywords($this->sections[$section]['id']);
        $result = [];
        $result['title'] = $this->sections[$section]['title'];
        foreach($categories as $id => $category) {
            $category = self::getCategory($id);
            self::getContent($id);
            $category['desc']  = CMS::call('PARSER')->parseText($category['desc']);
            $category['items'] = sizeof($this->content);
            if (!empty($this->content)) {
                $category['last'] = end($this->content);
                $category['last']['link'] = $category['link'].ITEM.$category['last']['id'];
            }
            $result['categories'][] = $category;
        }
        return $result;
    }

    /** Save section.
     * If parameter $id is not set, a new section will be created.
     * @throws Exception 'Invalid ID' - Empty ID or includes incorrect symbols
     * @throws Exception 'Title is empty' - Section tilte is not set
     * @throws Exception 'Cannot save section' - Cannot create directory for section or save index file
     * @return boolean The result of operation
     */
    public function saveSection() {
        $id = OnlyLatin(FILTER::get('REQUEST', 'section'));
        if ($id === FALSE) {
            throw new Exception('Invalid ID');
        }
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        if (!is_dir($this->container.$id)) {
            if (mkdir($this->container.$id, 0777) === FALSE) {
                throw new Exception('Cannot save section');
            }
            if ($this->saveIndex($this->container, []) === FALSE) {
                rmdir($this->container.$id);
                throw new Exception('Cannot save section');
            }
        }
        $this->sections[$id]['id']     = $id;
        $this->sections[$id]['title']  = $title;
        $this->sections[$id]['desc']   = FILTER::get('REQUEST', 'desc');
        $this->sections[$id]['access'] = intval(FILTER::get('REQUEST', 'access'));
        $this->sections[$id]['link']   = MODULE.$this->module.SECTION.$id;
        $this->sections[$id]['path']   = $this->container.$id.DS;
        if ($this->saveIndex($this->container, $this->sections) === FALSE) {
            DeleteTree($this->container.$id);
            throw new Exception('Cannot save section');
        }
        Sitemap();
    }

    /** Save all sections.
     * @param  array $sections Sections data
     * @throws Exception 'Cannot save sections' - Cannot save index file
     * @return void
     */
    public function saveSections($sections) {
        $new = [];
        foreach ($sections as $key => $section) {
            $new[$section] = $this->sections[$section];
        }
        $this->sections = $new;
        if ($this->saveIndex($this->container, $new) === FALSE) {
            throw new Exception('Cannot save sections');
        }
    }

    /** Remove section.
     * If parameter $id is not set, a new section will be created.
     * @throws Exception 'Invalid ID' - Section ID is empty
     * @throws Exception 'Cannot remove section' - Cannot delete the section directory tree
     * @return boolean The result of operation
     */
    public function removeSection($id) {
        if (empty($this->sections[$id])) {
            throw new Exception('Invalid ID');
        }
        unset($this->sections[$id]);
        if (($this->saveIndex($this->container, $this->sections) === FALSE) ||
            (DeleteTree($this->container.$id) === FALSE)) {
            throw new Exception('Cannot remove section');
        }
        Sitemap();
    }

    /** Get all categories of the requested section.
     * @return array Section categories allowed for current user
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

    /** Get requested category.
     * @param  integer $id Category ID
     * @return array       Category data
     */
    public function getCategory($id) {
        if (empty($this->sections[$this->section]['categories'][$id])) {
            return FALSE;
        }
        $this->category = $id;
        return $this->sections[$this->section]['categories'][$id];
    }

    /** Save category, if parameter $id is not set, a new category will be created.
     * @throws Exception 'Title is empty' - Category title is empty
     * @throws Exception 'Cannot create category' - Cannot create category directory or save index file
     * @return boolean The result
     */
    public function saveCategory() {
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $id = FILTER::get('REQUEST', 'category');
        if (empty($id)) {
            # Create new directory with empty index
            $id   = $this->newId($this->sections[$this->section]['categories']);
            $item = $this->sections[$this->section]['path'].$id;
            if (is_dir($item)) {
                if (!DeleteTree($item)) {
                    throw new Exception('Cannot save category');
                }
            }
            if ((mkdir($item, 0777) === FALSE) || ($this->saveIndex($item.DS, []) === FALSE)) {
                throw new Exception('Cannot save category');
            }
        }
        # Access level of the category should be more or is equal to access level of the section
        $access = (int) FILTER::get('REQUEST', 'access');
        $section_access = (int) $this->sections[$this->section]['access'];
        $this->sections[$this->section]['categories'][$id]['id']     = (int)$id;
        $this->sections[$this->section]['categories'][$id]['title']  = $title;
        $this->sections[$this->section]['categories'][$id]['desc']   = FILTER::get('REQUEST', 'desc');
        $this->sections[$this->section]['categories'][$id]['access'] = ($section_access >= $access) ? $section_access : $access;
        $this->sections[$this->section]['categories'][$id]['link']   = $this->sections[$this->section]['link'].CATEGORY.$id;
        $this->sections[$this->section]['categories'][$id]['path']   = $this->sections[$this->section]['path'].$id.DS;
        # If icon is not set an empty image will be shown
        self::setIcon($this->sections[$this->section]['categories'][$id]['path'], FILTER::get('REQUEST', 'icon'));
        if ($this->saveIndex($this->container, $this->sections) === FALSE) {
            throw new Exception('Cannot save category');
        }
        Sitemap();
    }

    /** Save all categories from requested section.
     * @param  string $section    Section name
     * @param  array  $categories Categories data
     * @throws Exception 'Cannot save categories' - Cannot save category index file
     * @return void
     */
    public function saveCategories($section, $categories) {
        $this->sections[$section]['categories'] = $categories;
        if ($this->saveIndex($this->container, $this->sections) === FALSE) {
            throw new Exception('Cannot save categories');
        }
    }

    /** Move category into another section.
     * @param  integer $id     ID of the category which will be moved
     * @param  string  $source Name of the source section
     * @param  string  $dest   Name of the destination section
     * @return integer|boolean ID of the new category or FALSE
     */
    public function moveCategory($id, $source, $dest) {
        if (empty($this->sections[$source])) {
            return FALSE;
        }
        if (empty($this->sections[$source]['categories'][$id])) {
            return FALSE;
        }
        $new = $this->newId($this->sections[$dest]['categories']);
        CopyTree($this->sections[$source]['path'].$id, $this->sections[$dest]['path'].$new);
        DeleteTree($this->sections[$source]['path'].$id);
        $this->sections[$dest]['categories'][$new] = $this->sections[$source]['categories'][$id];
        $this->sections[$dest]['categories'][$new]['link'] = $this->sections[$dest]['link'].CATEGORY.$new;
        $this->sections[$dest]['categories'][$new]['path'] = $this->sections[$dest]['path'].$new.DS;
        unset($this->sections[$source]['categories'][$id]);
        $this->saveIndex($this->container, $this->sections);
        return $new;
    }

    /** Remove category.
     * @param  integer $id Category ID
     * @throws Exception 'Cannot remove category' - Cannot remove category directory tree or save index file
     * @return boolean     The result
     */
    public function removeCategory($id) {
        unset($this->sections[$this->section]['categories'][$id]);
        if ((DeleteTree($this->sections[$this->section]['path'].$id) === FALSE) ||
            ($this->saveIndex($this->container, $this->sections) === FALSE)) {
            throw new Exception('Cannot remove category');
        }
        Sitemap();
    }

    /** Set icon for category.
     * @param  string  $path Path to destination directory
     * @param  array   $icon Image data
     * @return boolean       TRUE
     */
    protected function setIcon($path, $icon) {
        if (empty($icon['name']) && file_exists($path.'icon.png')) {
            return;
        }
        $IMAGE = new IMAGE($path, '', 35, 35);
        if ($IMAGE->upload($icon) === FALSE) {
            copy(ICONS.'icon.png', $path.'tmp.png');
            $IMAGE->setImage([
                'name'     => 'tmp.png',
                'size'     => 149,
                'tmp_name' => ''],
                [35, 35, 'mime' => 'image/png']
            );
        }
        return $IMAGE->generateIcon();
    }

    /** Get content from the requested category.
     * @param  integer $category Category ID
     * @return array|boolean     Category content or FALSE
     */
    public function getContent($category) {
        if (empty($this->sections[$this->section]['categories'][$category])) {
            return FALSE;
        }
        $this->category = $category;
        $this->content  = $this->getIndex($this->sections[$this->section]['categories'][$category]['path']);
        return $this->content;
    }

    /** Get item.
     * @param  integer $id    Item ID
     * @param  string  $type  Type of item: full text or description
     * @param  boolean $parse Parse text?
     * @return array          Item data
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
                if (!empty($item['desc'])) $item['desc'] = CMS::call('PARSER')->parseText($item['desc'], $path);
                if (!empty($item['text'])) {
                    $item['text'] = CMS::call('PARSER')->parseText($item['text'], $path);
                    if (CMS::call('USER')->checkRoot()) {
                        $item['admin'] = TRUE;
                        if (!empty($item['opened'])) {
                            $item['command'] = __('Close');
                            $item['action']  = 'close';
                        } else {
                            $item['command'] = __('Open');
                            $item['action']  = 'open';
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
        }
        return $item;
    }

    /** Save item.
     * @param  integer $id Item ID
     * @throws Exception 'Title is empty' - Item title is empty
     * @throws Exception 'Text is empty' - Item text is empty
     * @throws Exception 'Cannot remove item' - Cannot remove item old directory
     * @throws Exception 'Cannot create directory' - Cannot create item new directory
     * @return integer   ID of the saved item
     */
    public function saveItem($id) {
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = trim(FILTER::get('REQUEST', 'text'));
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $item = $this->sections[$this->section]['categories'][$this->category]['path'].$id;
        if (empty($id)) {
            $id = $this->newId($this->content);
            $item = $item.$id;
            if (is_dir($item)) {
                if (!DeleteTree($item)) {
                    throw new Exception('Cannot remove '.$item);
                }
            }
            if (mkdir($item, 0777) === FALSE) {
                throw new Exception('Cannot create directory');
            }
            $this->content[$id]['id']       = (int)$id;
            $this->content[$id]['author']   = USER::getUser('username');
            $this->content[$id]['nick']     = USER::getUser('nickname');
            $this->content[$id]['time']     = time();
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $this->content[$id]['title']    = $title;
        $this->content[$id]['keywords'] = preg_replace("/,[\s]+/", ',', FILTER::get('REQUEST', 'keywords'));
        $this->content[$id]['opened']   = (bool) FILTER::get('REQUEST', 'opened');
        $desc = FILTER::get('REQUEST', 'desc');
        if (empty($desc)) {
            $desc = CutText($text, CONFIG::getValue('posts', 'description-length'));
        }
        if ((file_put_contents($item.DS.$this->desc, $desc, LOCK_EX) === FALSE) ||
            (file_put_contents($item.DS.$this->text, $text, LOCK_EX) === FALSE)) {
            throw new Exception('Cannot save item');
        }
        self::saveContent($this->content);
        Sitemap();
        return $id;
    }

    /**
    * @todo Comment
    * @param string $id	...
    * @param string $section	...
    * @param string $category	...
    * @return integer ID of the new item
    */
    public function moveItem($id, $section, $category) {
        $item = $this->content[$id];
        $old_section  = $this->section;
        $old_category = $this->category;
        $this->section = $section;
        self::getContent($category);
        $new    = $this->newId($this->content);
        $source = $this->sections[$old_section]['categories'][$old_category]['path'];
        $dest   = $this->sections[$section]['categories'][$category]['path'];
        if (CopyTree($source.$id, $dest.$new) === FALSE) {
            rmdir($dest.$new);
            throw new Exception('Cannot move item');
        }
        $this->content[$new] = $item;
        $this->content[$new]['id'] = $new;
        self::saveContent($this->content);
        $this->section = $old_section;
        self::getContent($old_category);
        self::removeItem($item['id']);
        return $new;
    }

    /**
    * @todo Comment
    * @param string $content	...
    * @return
    */
    public function saveContent($content) {
        if ($this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'], $content) === FALSE) {
            throw new Exception('Cannot save content');
        }
    }

    /**
    * @todo Comment
    * @param string $id	...
    * @return
    */
    public function removeItem($id) {
        if (empty($this->content[$id])) {
            throw new Exception('Invalid ID');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        unset($this->content[$id]);
        if (($this->saveIndex($path, $this->content) === FALSE) || (DeleteTree($path.$id) === false)) {
            throw new Exception('Cannot remove item');
        }
        Sitemap();
    }

    /**
    * @todo Comment
    * @param string $id	...
    * @param string $field	...
    * @return
    */
    public function incCount($id, $field) {
        if (empty($this->content[$id])) {
            return FALSE;
        }
        $this->content[$id][$field]++;
        return $this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'], $this->content);
    }

    /**
    * @todo Comment
    * @param string $param	...
    * @return
    */
    public function getStat($param) {
        $result = [];
        foreach ($this->sections[$this->section]['categories'] as $category => $data) {
            self::getContent($category);
            foreach ($this->content as $key => $item) {
                $result[$category.'.'.$key] = $item[$param];
            }
        }
        return $result;
    }

    /**
    * @todo Comment
    * @param string $category	...
    * @param string $param	...
    * @param string $last	... (défaut : TRUE)
    * @param string $limit	... (défaut : '')
    * @return
    */
    public function getCategoryStat($category, $param, $last = TRUE, $limit = '') {
        $result = [];
        self::getContent($category);
        foreach ($this->content as $key => $item) {
            $result[$key] = $item[$param];
        }
        if ($limit) return array_slice($result, -$limit, $limit, TRUE);
        return $result;
    }

    /**
    * @todo Comment
    * @param string $items	...
    * @return
    */
    public function getLastItems($items) {
        krsort($items);
        $items  = array_slice($items, 0, (int) CONFIG::getValue('main', 'last'), TRUE);
        $result = [];
        foreach ($items as $key => $data) {
            $item = explode('.', $data);
            self::getCategories($item[0]);
            self::getContent($item[1]);
            $item = self::getItem($item[2]);
            $item['date'] = FormatTime('d F Y', $item['time']);
            $result['items'][] = $item;
        }
        return $result;
    }

    /**
    * @todo Comment
    * @param string $sections	... (défaut : '')
    * @return
    */
    public function getSectionsLastItems($sections = '') {
        $result = [];
        if (empty($sections)) $sections = $this->sections;
        foreach($sections as $id => $section) {
            self::getCategories($id);
            $last = $this->getStat('time');         # Get last items from section categories
            foreach ($last as $key => $time) {
                $result[$time] = $id.'.'.$key;      # Value is section.category.post
            }
        }
        return $result;
    }

    /**
    * @todo Comment
    * @param string $format	...
    * @return
    */
    public function getCategoryLastItems($format) {
        $items  = array_flip($this->getStat('time'));
        krsort($items);
        $result = [];
        if (!empty($items)) {
            $items = array_slice($items, 0, intval(CONFIG::getValue('main', 'last'), TRUE));
            foreach($items as $key => $data) {
                $id = explode('.', $data);
                self::getContent($id[0]);
                $item = self::getItem($id[1]);
                $item['date'] = FormatTime($format, $item['time']);
                $result['items'][] = $item;
            }
        }
        return $result;
    }

    /**
    * @todo Comment
    * @param string $item	...
    * @return
    */
    public function getComments($item) {
        $this->item = $item;
        if (!empty($this->comments)) {
            return $this->comments;
        }
        $this->comments = $this->getIndex($this->sections[$this->section]['categories'][$this->category]['path'].$item.DS);
        return $this->comments;
    }

    /**
    * @todo Comment
    * @param string $id	...
    * @param string $page	...
    * @return
    */
    public function getComment($id, $page) {
        if (empty($this->comments[$id])) {
            return FALSE;
        }
        $comment = $this->comments[$id];
        $comment['text']   = CMS::call('PARSER')->parseText($comment['text'], $this->sections[$this->section]['categories'][$this->category]['path'].$this->item.DS);
        $comment['date']   = FormatTime('d F Y H:i:s', $comment['time']);
        $comment['avatar'] = GetAvatar($comment['author']);
        $author = USER::getUserData($comment['author']);
        $comment['status']  = __($author['status']);
        $comment['stars']   = $author['stars'];
        $comment['country'] = $author['country'];
        $comment['city']    = $author['city'];

        $user = USER::getUser('username');
        if (($author['rights'] === '*') || ($user === $comment['author'])) {
            unset($comment['ip']);
        }
        if ($this->content[$this->item]['opened']) {
            if (($user !== 'guest') && ($user !== $comment['author'])) {
                $comment['opened'] = TRUE;
            }
            if (CMS::call('USER')->checkRoot() || (($author['rights'] !== '*') && USER::moderator($this->module, $this->comments[$id]))) {
                $comment['moderator'] = TRUE;
                $comment['link'] = $this->sections[$this->section]['categories'][$this->category]['link'].ITEM.$this->item;
                if (!empty($comment['ip'])) {
                    if ($page < 2) {
                        $comment['ban'] = $comment['link'];
                    } else {
                        $comment['ban'] = $comment['link'].PAGE.$page;
                    }
                }
            }
        }
        if (CONFIG::getValue('enabled', 'rate') && $user !== 'guest') {
            $comment['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
        }
        if ($comment['rate'] < 0) {
            $comment['rate_color'] = 'red';
        } elseif ($comment['rate'] === 0) {
            $comment['rate_color'] = 'black';
        } else {
            $comment['rate_color'] = 'green';
        }
        return $comment;
    }

    /** Get last comment or reply.
     * @return integer ID of the last comment or reply
     */
    public function getLastComment() {
        $last = array_pop($this->comments);
        array_push($this->comments, $last);
        return $last;
    }

    /** Save new comment or reply.
     * @param  integer $item ID of the article or reply
     * @param  string  $text Comment text
     * @throws Exception 'Invalid ID' - Invalid ID of the article or topic
     * @throws Exception 'Text is empty' - An attempt to write an empty article or topic
     * @throws Exception 'Cannot save comment' -  File system error or user have no rights to post
     * @return array List of comments related to article or topic
     */
    public function newComment($item, $text) {
        if (empty($this->content[$item])) {
            throw new Exception('Invalid ID');
        }
        $text = trim($text);
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        $id = $this->newId($this->comments);
        $this->comments[$id]['id']     = intval($id);
        $this->comments[$id]['author'] = USER::getUser('username');
        $this->comments[$id]['nick']   = USER::getUser('nickname');
        $this->comments[$id]['time']   = time();
        $this->comments[$id]['text']   = $text;
        $this->comments[$id]['ip']     = $_SERVER['REMOTE_ADDR'];
        $this->comments[$id]['rate']   = 0;
        $this->saveIndex($path.$item.DS, $this->comments);
        $this->content[$item]['comments']++;
        if ($this->saveIndex($path, $this->content) === FALSE) {
            throw new Exception('Cannot save comment');
        }
        return $this->content[$item]['comments'];
    }

    /** Save comment.
    * @param  string $id   Comment ID
    * @param  array  $item Comment item
    * @return array        Comment item
    */
    public function saveComment($id, $item) {
        if (!USER::loggedIn()) {
            throw new Exception('You are not logged in!');
        }
        if (empty($this->content[$item])) {
            throw new Exception('Invalid ID');
        }
        if (empty($this->content[$item]['opened'])) {
            throw new Exception('Comments are not allowed');
        }
        $text = trim(FILTER::get('REQUEST', 'text'));
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        if (empty($id)) {
            $this->newComment($item, $text);
        } else {
            if (empty($this->comments[$id])) {
                throw new Exception('Invalid ID');
            }
            $this->comments[$id]['text'] = $text;
            if ($this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'].$item.DS, $this->comments) === FALSE) {
                throw new Exception('Cannot save comment');
            }
            USER::changeProfileField(USER::getUser('username'), 'comments', '+');
        }
        FILTER::remove('REQUEST', 'text');
        return $this->content[$item]['comments'];
    }

    /** Remove comment.
    * @param  integer $id Comment ID
    * @return array       Comments
    */
    public function removeComment($id) {
        if (empty($this->comments[$id])) {
            throw new Exception('Invalid ID');
        }
        if (!USER::moderator($this->module, $this->comments[$id])) {
            throw new Exception('Cannot remove comment');
        }
        unset($this->comments[$id]);

        $path = $this->sections[$this->section]['categories'][$this->category]['path'];

        if (!empty($this->comments)) {
            $this->content[$this->item]['comments']--;
            if ($this->saveIndex($path.$this->item.DS, $this->comments) === FALSE) {
                throw new Exception('Cannot remove comment');
            }
        } else {
            $this->content[$this->item]['comments'] = 0;
            unlink($path.$this->item.DS.$this->index);
        }
        if ($this->saveIndex($path, $this->content) === FALSE) {
            throw new Exception('Cannot remove comment');
        }
        return $this->content[$this->item]['comments'];
    }
}
