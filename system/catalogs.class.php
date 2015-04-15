<?php
/** Работа с каталогами (файлов, ссылок и т.п.)
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/catalogs.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Catalogs
 */

class CATALOGS extends CONTENT {

    /** Инициализация класса */
    public function __construct() {
        parent::__construct();
        $this->module    = 'catalogs';
        $this->container = CATALOGS;
    }

    /** Получает комментарий к элементу каталога.
     * @var integer  $id   ID комментария
     * @var integer  $page Номер страницы комментариев
     * @return array       Комментарий и его атрибуты (в т.ч. текущий рейтинг комментария)
     */
    public function getComment($id, $page) {
        $comment = parent::getComment($id, $page);
        if (!empty($comment['rateid'])) {
            $comment['rateid'] = $this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
        }
        return $comment;
    }

    /** Загрузка файла на сервер.
     * @param  integer   $id   ID файла
     * @param  array     $file Массив данных $_FILES
     * @throws Exception       Если нечего грузить
     * @return array           Имя файла и его размер
     * @uses class UPLOADER
     */
    public function uploadFile($id, $file) {
        $UPLOAD = new UPLOADER($this->sections[$this->section]['categories'][$this->category]['path'].$id.DS);
        return $UPLOAD->upload($file);
    }
}
