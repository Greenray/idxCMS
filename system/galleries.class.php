<?php
# idxCMS Flat Files Content Management Sysytem

/** "Фотоальбомы".
 * Это очень простая фотогалерея. Грузишь фотку, на станице её миниатюра.
 * Кликнул - раскрылась на весь экран. Ну, если она не для паспорта...
 * В админке их можно тусовать.
 *
 * @file      system/galleries.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Galleries
 */

class GALLERIES extends CONTENT {

	/** Инициалиализация.
     *
     * Устанавливает имя модуля и путь к каталогам хранения фоток.
	 * @return void
	 */
    public function __construct() {
        $this->module = 'galleries';
        $this->container = GALLERIES;
    }

    /** Сохраняет изо.
     *
     * @param  integer $id ID изо. Остальное получает из массива REQUEST
     * @throws Exception Нет названия изо, описания, прав на запись
     * @return integer     ID изо при успехе
     */
    public function saveItem($id) {
        $title = FILTER::get('REQUEST', 'title');
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path  = $this->sections[$this->section]['categories'][$this->category]['path'];
        $file  = FILTER::get('REQUEST', 'file');
        $image = FILTER::get('REQUEST', 'image');
        if (empty($id)) {
            if (empty($file) && empty($image)) {
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
        }
        if (!empty($file['name'])) {
            try {
                $uploaded = self::uploadFile($id, $file);
            } catch (Exception $error) {
                throw new Exception(__($error->getMessage()));
            }
            $this->content[$id]['file'] = $uploaded[0];
            $this->content[$id]['size'] = (int) $uploaded[1];
            $this->content[$id]['downloads'] = 0;
        } else {
            if (!empty($image['name'])) {
                try {
                    $uploaded = self::uploadImage($id, $image);
                } catch (Exception $error) {
                    throw new Exception(__($error->getMessage()));
                }
                $this->content[$id]['image'] = $uploaded;
            }
        }
        $this->content[$id]['title']     = $title;
        $this->content[$id]['keywords']  = FILTER::get('REQUEST', 'keywords');
        $this->content[$id]['copyright'] = FILTER::get('REQUEST', 'copyright');
        $this->content[$id]['opened']    = (bool) FILTER::get('REQUEST', 'opened');
        $desc = FILTER::get('REQUEST', 'desc');
        if (empty($desc)) {
            $desc = CutText($text, CONFIG::getValue('galleries', 'description-length'));
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

    public function getImage($id, $content = '', $parse = TRUE) {
        $image = parent::getItem($id, $content, $parse);
        if (CONFIG::getValue('enabled', 'rate')) {
            $image['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$id;
            $image['rate'] = ShowRate($image['rateid']);
        }
        return $image;
    }

    /** Сохраняет изо.
     *
     * @param  integer    $id ID изо. Остальное получает из массива REQUEST
     * @throws Exceptions Нет названия, описания, нечего грузить, ошибка записи.
     * @todo Разобраться с saveImage vs saveItem
     */
    public function saveImage($id) {
        $title = FILTER::get('REQUEST', 'title');
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path  = $this->sections[$this->section]['categories'][$this->category]['path'];
        $image = FILTER::get('REQUEST', 'image');
        if (empty($id)) {
            if (empty($image)) {
                throw new Exception('Nothing to upload');
            }
            $id = $this->newId($this->content);
            if (mkdir($path.$id, 0777) === FALSE) {
                throw new Exception('Cannot save image');
            }
            $this->content[$id]['id']       = (int)$id;
            $this->content[$id]['author']   = USER::getUser('username');
            $this->content[$id]['nick']     = USER::getUser('nickname');
            $this->content[$id]['time']     = time();
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        if (!empty($image['name'])) {
            try {
                $uploaded = self::uploadImage($id, $image);
            } catch (Exception $error) {
                rmdir($path.$id);
                throw new Exception(__($error->getMessage()));
            }
            $this->content[$id]['image'] = $uploaded;
        }
        $this->content[$id]['title']    = $title;
        $this->content[$id]['keywords'] = FILTER::get('REQUEST', 'keywords');
        $this->content[$id]['opened']   = (bool) FILTER::get('REQUEST', 'opened');
        if (file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX) === FALSE) {
            throw new Exception('Cannot save image');
        }
        parent::saveContent($this->content);
        Sitemap();
    }

    private function uploadImage($id, $image) {
        if (empty($image['name'])) {
            throw new Exception('Nothing to upload');
        }
        $IMAGE = new IMAGE($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        $img   = $IMAGE->upload($image);
        $IMAGE->generateThumbnail();
        return $img;
    }

    /** Выдает случаным образом выбранную фотку.
     *
     * Шустрит по всем каталогам и фоткам и не факт одна и та же фотка не вылезет
     * дважды подряд. Особенно когда их мало.
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

    public function getComment($id, $page) {
        $comment = parent::getComment($id, $page);
        if (!empty($comment['rateid'])) {
            $comment['rateid'] = $this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
        }
        return $comment;
    }
}