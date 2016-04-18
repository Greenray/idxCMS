<?php
/**
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/modules/user/module.php
 * @package   User
 */
if (!defined('idxCMS')) die();

/** Data storage for private messages */
define('PM_DATA', CONTENT.'pm'.DS);
/** Data storage for user's avatars */
define('AVATARS', CONTENT.'avatars'.DS);

require SYS.'message.class.php';

/**
 * Creates link for user profile.
 *
 * @param  string $user Username
 * @param  string $nick Nickname
 * @return string       Link for user profile
 */
function CreateUserLink($user, $nick) {
    if ($user === 'guest') {
        return $nick;
    }
    return '<a href="'.MODULE.'user&amp;user='.$user.'">'.$nick.'</a>';
}

/**
 * Gets user avatar.
 *
 * @param  string $user Username
 * @return string       Avatar image with full path
 */
function GetAvatar($user) {
    if (file_exists(AVATARS.$user.'.png'))
         return AVATARS.$user.'.png';
    else return AVATARS.'noavatar.gif';
}

/**
 * Constructs a drop-down list of items.
 *
 * @param  string $name    The name of the list
 * @param  array  $points  Values of options
 * @param  string $default Default value
 * @param  string $script  Javascript
 * @return string          A drop-duwn list of items
 */
function SelectPoint($name, $points, $default, $script) {
    $result = '<select name="'.$name.'" '.$script.'>';
    foreach ($points as $id => $point) {
        $result .= '<option value="'.$point.'"'.(($default === $point) ? ' selected>' : '>').ucfirst($point).'</option>';
    }
    $result .= '</select>';
    return $result;
}

SYSTEM::registerModule('user',          'User',             'main', 'system');
SYSTEM::registerModule('user.panel',    'User panel',       'box',  'system');
SYSTEM::registerModule('user.pm',       'Private messages', 'main', 'system');
SYSTEM::registerModule('user.feedback', 'Feedback',         'main', 'system');
