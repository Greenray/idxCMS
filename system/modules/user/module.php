<?php
/** 
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/user/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   User
 */
if (!defined('idxCMS')) die();

/** Data storage for private messages */
define('PM_DATA', CONTENT.'pm'.DS);
/** Data storage for user's avatars */
define('AVATARS', CONTENT.'avatars'.DS);

require SYS.'message.class.php';

/** Create link for user profile.
 * @param  string $user Username
 * @param  string $nick Nickname
 * @return string       Link for user profile
 */
function CreateUserLink($user, $nick) {
    if ($user === 'guest') {
        return __('Guest');
    }
    return '<a href="'.MODULE.'user&amp;user='.$user.'">'.$nick.'</a>';
}

/** Get user avatar.
 * @param  string $user Username
 * @return string       Avatar image with full path
 */
function GetAvatar($user) {
    if (file_exists(AVATARS.$user.'.png'))
         return AVATARS.$user.'.png';
    else return AVATARS.'noavatar.gif';
}

SYSTEM::registerModule('user',          'User',             'main', 'system');
SYSTEM::registerModule('user.panel',    'User panel',       'box',  'system');
SYSTEM::registerModule('user.pm',       'Private messages', 'main', 'system');
SYSTEM::registerModule('user.feedback', 'Feedback',         'main', 'system');
