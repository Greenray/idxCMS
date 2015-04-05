<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
        array(
            'loggedin'    => USER::loggedIn(),
            'user'        => USER::getUser('nickname'),
            'admin'       => CMS::call('USER')->checkRoot(),
            'mess_new'    => $info[0],
            'mess_info'   => $info[1],
            'allow_skins' => CONFIG::getValue('main', 'allow-skin'),
            'select_skin' => SelectPoint(
                'skin',
                AdvScanDir(SKINS, '', 'dir', FALSE, array('images')),
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
        )
    )
);
