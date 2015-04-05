<?php
/** Module USERS - User's panel.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/modules/user/module.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Users
 */

if (!defined('idxCMS')) die();

/** Creates html tag <option>
 *
 * @param  array  $points  Attributes for html tag <option>
 * @param  string $default Defaul value for html tag <select>
 * @return string html tag <option>
 */
function SelectPoint($points, $default) {
    $output = [];
    foreach ($points as $id => $point) {
        $output[$id]['point']    = $point;
        $output[$id]['title']    = ucfirst($point);
        $output[$id]['selected'] = ($default === $point) ? TRUE : NULL;
    }
    return $output;
}

$PM   = new MESSAGE(PM_DATA, USER::getUser('username'));
$info = $PM->checkNewMessages();
unset($PM);

$TPL = new TEMPLATE(dirname(__FILE__).DS.'panel.tpl');
ShowWindow(
    __('User panel'),
    $TPL->parse([
        'loggedin'    => USER::loggedIn(),
        'user'        => USER::getUser('nickname'),
        'admin'       => CMS::call('USER')->checkRoot(),
        'mess_new'    => $info[0],
        'mess_info'   => $info[1],
        'allow_skins' => CONFIG::getValue('main', 'allow-skin'),
        'select_skin' => SelectPoint(AdvScanDir(SKINS, '', 'dir', FALSE, ['images']), SYSTEM::get('skin')),
        'allow_langs' => CONFIG::getValue('main', 'allow-lang'),
        'select_lang' => SelectPoint(SYSTEM::get('languages'), SYSTEM::get('language'))
    ])
);
