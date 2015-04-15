<?php
/** "Фотоальбомы".
 * Это очень простая фотогалерея. Грузишь фотку, на станице её миниатюра.
 * Кликнул - раскрылась на весь экран. Ну, если она не для паспорта...
 * В админке их можно тусовать.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/galleries.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Galleries
 */

class GALLERIES extends CONTENT {

	/** Инициалиализация.
     * Устанавливает имя модуля и путь к каталогам хранения фото.
	 */
    public function __construct() {
        parent::__construct();
        $this->module    = 'galleries';
        $this->container = GALLERIES;
    }

    /** Gets comment.
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

    /** Gets image info.
     * @param  integer $id    Item ID
     * @param  string  $type  Type: full text or description (default = '')
     * @param  boolean $parse Parse text? (default = TRUE)
     * @return array          Image info
     */
    public function getImage($id, $type = '', $parse = TRUE) {
        $image = parent::getItem($id, $type, $parse);
        if (CONFIG::getValue('enabled', 'rate')) {
            $image['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$id;
            $image['rate'] = ShowRate($image['rateid']);
        }
        return $image;
    }

    /** Выдает случаным образом выбранное фото.
     * Шустрит по всем каталогам и фото, и не факт, что одна и та же картинка не вылезет дважды подряд.
     * Особенно когда их мало.
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

    /** Uploads image.
     * @param  integer $id    ID of the file
     * @param  string  $image Filename
     * @return boolean        The result of operation
     */
    public function uploadImage($id, $image) {
        $IMAGE = new IMAGE($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        $img   = $IMAGE->upload($image);
        $IMAGE->generateThumbnail();
        return $img;
    }
}
