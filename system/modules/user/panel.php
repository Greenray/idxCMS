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

function SelectPoint($name, $points, $default, $script) {
    $result = '<select name="'.$name.'" style="width:90%;" '.$script.'>';
    foreach ($points as $id => $point) {
        $result .= '<option value="'.$point.'"'.(($default === $point) ? ' selected="selected">' : '>').ucfirst($point).'</option>';
    }
    $result .= '</select>';
    return $result;
}

$PM   = new MESSAGE(PM_DATA, USER::getUser('username'));
$info = $PM->checkNewMessages();
unset($PM);

$TPL = new TEMPLATE(dirname(__FILE__).DS.'panel.tpl');
ShowWindow(
    __('User panel'),
    $TPL->parse(
        [
            'logged_in'    => USER::$logged_in,
            'user'        => USER::getUser('nickname'),
            'admin'       => USER::$root,
            'mess_new'    => $info[0],
            'mess_info'   => $info[1],
            'allow_skins' => CONFIG::getValue('main', 'allow-skin'),
            'select_skin' => SelectPoint(
                'skin',
                AdvScanDir(SKINS, '', 'dir', FALSE, ['images']),
                SYSTEM::get('skin'),
                'onchange="document.forms[\'skin_select\'].submit()" title="'.__('Skin').'"'
            ),
            'allow_langs' => CONFIG::getValue('main', 'allow-lang'),
            'select_lang' => SelectPoint(
                'language',
                SYSTEM::get('languages'),
                SYSTEM::get('language'),
                'onchange="document.forms[\'lang_select\'].submit()" title="'.__('Language').'"'
            )
        ]
    )
);
