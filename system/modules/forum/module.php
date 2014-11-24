<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE FORUM - INITIALIZATION

if (!defined('idxCMS')) die();

/** Forum data store */
define('FORUM', CONTENT.'forum'.DS);

# FORUM class
class FORUM extends CONTENT {

    public function __construct() {
        $this->module = 'forum';
        $this->container = FORUM;
    }

    public function getItem($id, $type = '', $parse = TRUE) {
        return parent::getItem($id, 'text', $parse);
    }

    public function saveTopic($id = '') {
        if (!USER::loggedIn()) {
            throw new Exception('You are not logged in!');
        }
        $title = trim(FILTER::get('REQUEST', 'title'));
        if ($title === FALSE) {
            throw new Exception('Title is empty');
        }
        $text = trim(FILTER::get('REQUEST', 'text'));
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        if (empty($id)) {
            $id = $this->newId($this->content);
            if (mkdir($path.$id, 0777) === FALSE) {
                throw new Exception('Cannot save topic');
            }
            $this->content[$id]['id']       = (int) ($id);
            $this->content[$id]['author']   = USER::getUser('username');
            $this->content[$id]['nick']     = USER::getUser('nickname');
            $this->content[$id]['time']     = time();
            $this->content[$id]['ip']       = $_SERVER['REMOTE_ADDR'];
            $this->content[$id]['views']    = 0;
            $this->content[$id]['comments'] = 0;
        }
        $this->content[$id]['title']  = $title;
        $this->content[$id]['opened'] = (int) empty(FILTER::get('REQUEST', 'opened')) ? 1 : 0;
        $this->content[$id]['pinned'] = (int) FILTER::get('REQUEST', 'pinned');
        if (file_put_contents($path.$id.DS.$this->text, $text, LOCK_EX) === FALSE) {
            throw new Exception('Cannot save topic');
        }
        parent::saveContent($this->content);
        Sitemap();
        return $id;
    }
}

# ArraySort callback
function ArraySortFunc($a, $b = NULL) {
    static $keys;
    if ($b === NULL) {
        return $keys = $a;
    }
    foreach ($keys as $key) {
        if (@$key[0] == '!') {
            $key = substr($key, 1);
            if (@$a[$key] !== @$b[$key]) {
                return strcmp(@$b[$key], @$a[$key]);
            }
        } elseif (@$a[$key] !== @$b[$key]) {
            return strcmp(@$a[$key], @$b[$key]);
        }
    }
    return FALSE;
}

function ArraySort(&$array) {
    $keys = array();
    if (!$array) {
        return $keys;
    }
    $keys = func_get_args();
    array_shift($keys);
    ArraySortFunc($keys);
    uasort($array, "ArraySortFunc");
}

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Cannot save topic'] = 'Не могу сохранить тему';
        $LANG['def']['Forum'] = 'Форум';
        $LANG['def']['Last topics'] = 'Последние темы';
        $LANG['def']['New topic'] = 'Новая тема';
        $LANG['def']['Pin'] = 'Прикрепить';
        $LANG['def']['Reply'] = 'Ответ';
        $LANG['def']['Replies'] = 'Ответы';
        $LANG['def']['Reply editing'] = 'Редактирование ответа';
        $LANG['def']['Topic'] = 'Тема';
        $LANG['def']['Topics'] = 'Темы';
        $LANG['def']['Unpin'] = 'Открепить';
        break;
    case 'ua':
        $LANG['def']['Cannot save topic'] = 'Не можу зберегти тему';
        $LANG['def']['Forum'] = 'Форум';
        $LANG['def']['Last topics'] = 'Останні повідомлення';
        $LANG['def']['New topic'] = 'Нова тема';
        $LANG['def']['Pin'] = 'Прикріпити';
        $LANG['def']['Reply'] = 'Відповідь';
        $LANG['def']['Replies'] = 'Відповіді';
        $LANG['def']['Reply editing'] = 'Редагування відповіді';
        $LANG['def']['Topic'] = 'Тема';
        $LANG['def']['Topics'] = 'Теми';
        $LANG['def']['Unpin'] = 'Відкріпити';
        break;
    case 'by':
        $LANG['def']['Cannot save topic'] = 'Не магу захаваць тэму';
        $LANG['def']['Forum'] = 'Форум';
        $LANG['def']['Last topics'] = 'Апошнія паведамленні';
        $LANG['def']['New topic'] = 'Новая тэма';
        $LANG['def']['Pin'] = 'Прымацаваць';
        $LANG['def']['Reply'] = 'Адказ';
        $LANG['def']['Replies'] = 'Адказы';
        $LANG['def']['Reply editing'] = 'Рэдагаванне адказу';
        $LANG['def']['Topic'] = 'Тэма';
        $LANG['def']['Topics'] = 'Тэмы';
        $LANG['def']['Unpin'] = 'Распушчае мацаваньне';
        break;
}

SYSTEM::registerModule('forum', 'Forum', 'main');
SYSTEM::registerModule('forum.last', 'Last topics', 'box');
USER::setSystemRights(array('forum' => __('Forum').': '.__('Moderator')));
SYSTEM::registerMainMenu('forum');
SYSTEM::registerSiteMap('forum');
SYSTEM::registerSearch('forum');

$sections = CMS::call('FORUM')->getSections();

if (!empty($sections['archive'])) unset($sections['archive']);
foreach ($sections as $id => $section) {
    if ($section['access'] === 0) {
        SYSTEM::registerFeed(
            'forum@'.$id,
            $section['title'],
            __('RSS for section').' '.$section['title'],
            'forum'
        );
    }
}
