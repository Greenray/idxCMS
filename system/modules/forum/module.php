<?php
# idxCMS Flat Files Content Management Sysytem

/** Forum.
 * Module registration and internal functions.
 * @file      system/modules/forum/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Forum
 */

if (!defined('idxCMS')) die();

/** Forum data store. */
define('FORUM', CONTENT.'forum'.DS);

require SYS.'forum.class.php';

/** Sort of array.
 * @param  array $array Array to sort
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

/** ArraySort callback.
 * String comparison.
 * @param  array   $a Fist array to compare
 * @param  array   $b Second array to compare (Default NULL)
 * @return boolean    The result of operation
 */
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

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Cannot save topic'] = 'Не могу сохранить тему';
        $LANG['def']['Forum'] = 'Форум';
        $LANG['def']['Last topics'] = 'Последние темы';
        $LANG['def']['New topic'] = 'Новая тема';
        $LANG['def']['Pin'] = 'Прикрепить';
        $LANG['def']['Reply'] = 'Ответ';
//        $LANG['def']['Replies'] = 'Ответы';
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
//        $LANG['def']['Replies'] = 'Відповіді';
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
//        $LANG['def']['Replies'] = 'Адказы';
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
