<?php
/**
 * Images albums.
 * Very simple images gallery.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/gallery.class.php
 * @package   Gallery
 */

class GALLERY extends CONTENT {

	/**
     * Class constructor.
     * Sets module name and a path to images storage.
	 */
    public function __construct() {
        parent::__construct();
        $this->module    = 'gallery';
        $this->container = GALLERY;
    }

    /**
     * Gets comment.
     *
     * @param  string $id   Comment ID
     * @param  string $page Page number
     * @return array        Comment data
     */
    public function getComment($id, $page) {
        $comment = parent::getComment($id, $page);
        if (!empty($comment['rateid'])) {
            $comment['rateid'] = $this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
        }
        return $comment;
    }

    /**
     * Gets image info.
     *
     * @param  integer $id    Item ID
     * @param  string  $type  Type: full text or description (default = '')
     * @param  boolean $parse Parse text? (default = TRUE)
     * @return array          Image info
     */
    public function getImage($id, $type = '', $parse = TRUE) {
        $image = parent::getItem($id, $type, $parse);
        if (CONFIG::getValue('enabled', 'rate')) {
            $image['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$id;
            $image['rate']   = ShowRate($image['rateid']);
        }
        return $image;
    }

    /**
     * Выдает случаным образом выбранное фото.
     * Шустрит по всем каталогам и фото, и не факт, что одна и та же картинка не вылезет дважды подряд.
     * Особенно когда их мало.
     *
     * @param  integer $id
     * @return boolean Результат
     */
    public function getRandomImage($id) {
        $images = parent::getContent($id);
        if (empty($images)) {
            return FALSE;
        }
        $i = mt_rand(1, sizeof($images));
        return $this->getImage($i, '', FALSE);
    }

    /**
     * Saves image.
     * This function corrects website sitemap.
     *
     * @param  integer    $id                 Item ID
     * @throws Exception "Title is empty"     Title is empty or has wrong symbols
     * @throws Exception "Text is empty"
     * @throws Exception "Cannot create item" No access rights
     * @return integer                        ID of the saved item
     */
    public function saveImage($id) {
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
            $id   = $this->newId($this->content);
            $item = $path.$id;
            if (is_dir($item)) {
                if (!DeleteTree($item)) {
                    throw new Exception('Cannot create'.' '.$item);
                }
            }
            if (!mkdir($path.$id, 0777)) {
                throw new Exception('Cannot create'.' '.$item);
            }
            $this->content[$id]['id']       = $id;
            $this->content[$id]['author']   = USER::getUser('user');
            $this->content[$id]['nick']     = USER::getUser('nick');
            $this->content[$id]['time']     = time();
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $image = FILTER::get('REQUEST', 'image');
        if (!empty($image['name'])) {
            try {
                $uploaded = $this->uploadImage($id, $image);
            } catch (Exception $error) {
                throw new Exception($error->getMessage());
            }
            $this->content[$id]['image'] = $uploaded;
        }
        $this->content[$id]['title']     = $title;
        $this->content[$id]['keywords']  = FILTER::get('REQUEST', 'keywords');
        $this->content[$id]['copyright'] = FILTER::get('REQUEST', 'copyright');
        $this->content[$id]['opened']    = FILTER::get('REQUEST', 'opened');
        $desc = FILTER::get('REQUEST', 'desc');
        if (empty($desc)) {
            $desc = $this->cutText($text, CONFIG::getValue('gallery', 'description_length'));
        }
        if (!file_put_contents($path.$id.DS.$this->desc, $desc, LOCK_EX)) {
            throw new Exception('Cannot save file'.' '.$path.$id.DS.$this->desc);
        }
        if (!file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX)) {
            throw new Exception('Cannot save file'.' '.$path.$id.DS.$this->text);
        }
        self::saveContent($this->content);
        Sitemap();
        return $id;
    }

    /**
     * Uploads image.
     *
     * @param  integer $id    ID of the file
     * @param  string  $image Filename
     * @return string|boolean Image name or FALSE
     */
    public function uploadImage($id, $image) {
        $IMAGE = new IMAGE($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        $image = $IMAGE->upload($image);
        if (!empty($image)) {
            $IMAGE->generateThumbnail();
        }
        return $image;
    }
}
