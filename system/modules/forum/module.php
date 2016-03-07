<?php
/**
 * Forum.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/modules/forum/module.php
 * @package   Forum
 * @overview  Website forum.
 *            Forum database is similar to posts database.
 */

if (!defined('idxCMS')) die();

/** Data storage for forum */
define('FORUM', CONTENT.'forum'.DS);

require SYS.'forum.class.php';

/**
 * Sorts array.
 *
 * @param  array $array Link to array to sort
 * @return array        Sorted array
 */
function ArraySort(&$array) {
    $keys = [];
    if (!$array) {
        return $keys;
    }
    $keys = func_get_args();
    array_shift($keys);
    ArraySortFunc($keys);
    uasort($array, "ArraySortFunc");
}

/**
 * ArraySort callback.
 * String comparison.
 *
 * @param  array   $a Fist array to compare
 * @param  array   $b Second array to compare (Default NULL)
 * @return boolean    The result of operation
 */
function ArraySortFunc($a, $b = NULL) {
    static $keys;
    if ($b === NULL) {
        return $a;
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

SYSTEM::registerMainMenu('forum');
SYSTEM::registerSiteMap('forum');
SYSTEM::registerSearch('forum');

USER::setSystemRights(['forum' => __('Forum').': '.__('Moderator')]);

$sections = CMS::call('FORUM')->getSections();

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
