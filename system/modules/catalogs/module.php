<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE CATALOGS - INITIALIZATION

if (!defined('idxCMS')) die();

define('CATALOGS', CONTENT.'catalogs'.DS);

class CATALOGS extends CONTENT {

    public function __construct() {
        $this->module = 'catalogs';
        $this->container = CATALOGS;
    }

    public function saveItem($id) {
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = trim(FILTER::get('REQUEST', 'text'));
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

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Download'] = 'Скачать';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Ссылки';
        $LANG['def']['Size'] = 'Размер';
        $LANG['def']['Transitions'] = 'Переходов';
        $LANG['def']['Updates'] = 'Обновления';
        break;
    case 'ua':
        $LANG['def']['Catalogs'] = 'Каталоги';
        $LANG['def']['Download'] = 'Скачати';
        $LANG['def']['Downloads'] = 'Скачан';
        $LANG['def']['Files'] = 'Файлi';
        $LANG['def']['Go'] = 'Перейти';
        $LANG['def']['Links'] = 'Посилання';
        $LANG['def']['Size'] = 'Розмір';
        $LANG['def']['Transitions'] = 'Переходів';
        $LANG['def']['Updates'] = 'Оновлення';
        break;
    case 'by':
        $LANG['def']['Catalogs'] = 'Каталогі';
        $LANG['def']['Download'] = 'Запампаваць';
        $LANG['def']['Downloads'] = 'запампаваны';
        $LANG['def']['Files'] = 'Файлы';
        $LANG['def']['Go'] = 'Перайсці';
        $LANG['def']['Links'] = 'Спасылкі';
        $LANG['def']['Size'] = 'Памер';
        $LANG['def']['Transitions'] = 'Пераходаў';
        $LANG['def']['Updates'] = 'Абнаўленні';
        break;
}

SYSTEM::registerModule('catalogs', 'Catalogs', 'main');
SYSTEM::registerModule('catalogs.last', 'Updates', 'box');
USER::setSystemRights(array('catalogs' => __('Catalogs').': '.__('Moderator')));
SYSTEM::registerMainMenu('catalogs');
SYSTEM::registerSiteMap('catalogs');
SYSTEM::registerSearch('catalogs');
?>