<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Main configuration manager.

if (!defined('idxADMIN') || !USER::$root) die();
#
# Save CMS configuration
#
if (!empty($REQUEST['save'])) {
    $config = [];
    $config['title'] = !empty($REQUEST['title']) ? $REQUEST['title'] : 'idxCMS';
    if (!empty($REQUEST['url'])) {
        $config['url'] = $REQUEST['url'];
        if ($config['url']{strlen($config['url']) - 1} !== DS) {
            $config['url'] .= DS;
        }
    } else  $config['url'] = '';

    $config['description']   = !empty($REQUEST['description'])   ? $REQUEST['description']   : 'Система управления сайтом idxCMS';
    $config['slogan']        = !empty($REQUEST['slogan'])        ? $REQUEST['slogan']        : 'Пока не треснешь как следует, ничего не заработает (В.В.Путин)';
    $config['random_slogan'] = !empty($REQUEST['random_slogan']) ? $REQUEST['random_slogan'] : '1';
    $config['cookie']        = !empty($REQUEST['cookie'])        ? $REQUEST['cookie']        : 'idxCMS';

    if (empty($REQUEST['keywords']))
         $config['keywords'] = 'idxCMS,CMS,opensource,php';
    else $config['keywords'] = preg_replace('/\s/', '', $REQUEST['keywords']);

    $config['skin']            = empty($REQUEST['skin'])            ? 'Default' : $REQUEST['skin'];
    $config['lang']            = empty($REQUEST['lang'])            ? 'russian' : $REQUEST['lang'];
    $config['allow_skin']      = empty($REQUEST['allow_skin'])      ? ''        : '1';
    $config['allow_language']  = empty($REQUEST['allow_language'])  ? ''        : '1';
    $config['detect_language'] = empty($REQUEST['detect_language']) ? ''        : '1';
    $config['per_page']        = empty($REQUEST['per_page'])        ? 10        : $REQUEST['per_page'];
    $config['last']            = empty($REQUEST['last'])            ? 10        : $REQUEST['last'];
    $config['max_filesize']    = empty($REQUEST['max_filesize'])    ? 2000000   : $REQUEST['max_filesize'];
    $config['thumb_width']     = empty($REQUEST['thumb_width'])     ? 200       : $REQUEST['thumb_width'];
    $config['thumb_height']    = empty($REQUEST['thumb_height'])    ? 150       : $REQUEST['thumb_height'];
    $config['captcha']         = empty($REQUEST['captcha'])         ? 'Random'  : $REQUEST['captcha'];
    $config['tz']              = empty($REQUEST['tz'])              ? 4         : $REQUEST['tz'];
    $config['welcome']         = empty($REQUEST['welcome'])         ? ''        : '1';
    CMS::call('CONFIG')->setSection('main', $config);

    $config = [];
    $config['query_min']   = empty($REQUEST['query_min'])   ? 6  : $REQUEST['query_min'];
    $config['query_max']   = empty($REQUEST['query_max'])   ? 20 : $REQUEST['query_max'];
    $config['block']       = empty($REQUEST['block'])       ? 50 : $REQUEST['block'];
    $config['per_page']    = empty($REQUEST['per_page'])    ? 20 : $REQUEST['per_page'];
    $config['allow_guest'] = empty($REQUEST['allow_guest']) ? '' : '1';
    CMS::call('CONFIG')->setSection('search', $config);

    $config = [];
    $config['width']          = empty($REQUEST['width'])          ? 290        : $REQUEST['width'];
    $config['height']         = empty($REQUEST['height'])         ? 24         : $REQUEST['height'];
    $config['bgcolor']        = empty($REQUEST['bgcolor'])        ? '0x66ff66' : strtr($REQUEST['bgcolor'],        ['#' => '0x']);
    $config['leftbg']         = empty($REQUEST['leftbg'])         ? '0x969696' : strtr($REQUEST['leftbg'],         ['#' => '0x']);
    $config['lefticon']       = empty($REQUEST['lefticon'])       ? '0x3333ff' : strtr($REQUEST['lefticon'],       ['#' => '0x']);
    $config['rightbg']        = empty($REQUEST['rightbg'])        ? '0x394670' : strtr($REQUEST['rightbg'],        ['#' => '0x']);
    $config['righticon']      = empty($REQUEST['righticon'])      ? '0xffffff' : strtr($REQUEST['righticon'],      ['#' => '0x']);
    $config['rightbghover']   = empty($REQUEST['rightbghover'])   ? '0xff0000' : strtr($REQUEST['rightbghover'],   ['#' => '0x']);
    $config['righticonhover'] = empty($REQUEST['righticonhover']) ? '0xffffff' : strtr($REQUEST['righticonhover'], ['#' => '0x']);
    $config['playertext']     = empty($REQUEST['playertext'])     ? '0x000000' : strtr($REQUEST['playertext'],     ['#' => '0x']);
    $config['slider']         = empty($REQUEST['slider'])         ? '0xff0000' : strtr($REQUEST['slider'],         ['#' => '0x']);
    $config['track']          = empty($REQUEST['track'])          ? '0xffffff' : strtr($REQUEST['track'],          ['#' => '0x']);
    $config['border']         = empty($REQUEST['border'])         ? '0x666666' : strtr($REQUEST['border'],         ['#' => '0x']);
    $config['loader']         = empty($REQUEST['loader'])         ? '0xffffff' : strtr($REQUEST['loader'],         ['#' => '0x']);
    $config['autostart']      = empty($REQUEST['autostart'])      ? '' : '1';
    $config['loop']           = empty($REQUEST['loop'])           ? '' : '1';
    CMS::call('CONFIG')->setSection('audio', $config);

    $config = [];
    $config['width']  = empty($REQUEST['width'])  ? 435 : $REQUEST['width'];
    $config['height'] = empty($REQUEST['height']) ? 350 : $REQUEST['height'];
    CMS::call('CONFIG')->setSection('video', $config);
    if (CMS::call('CONFIG')->save())
         ShowMessage('Configuration has been saved');
    else ShowError('Cannot save file'.' config.ini');

    if (!empty($REQUEST['welcome_msg'])) {
        if (!file_put_contents(CONTENT.'intro', CMS::call('PARSER')->parseText($REQUEST['welcome_msg']))) {
            ShowError('Cannot save file'.' intro');
        }
    }
}
#
# INTERFACE
#
$current  = CONFIG::getSection('main');
$current += CONFIG::getSection('search');
$current += CONFIG::getSection('audio');
$current += CONFIG::getSection('video');

$config = $current;
$config['php_max_filesize'] = ini_get('upload_max_filesize');
#
# Default skin
#
$skins = AdvScanDir(SKINS, '', 'dir', FALSE, ['images']);
foreach ($skins as $key => $skin) {
    $config['skins'][$key]['skin'] = $skin;
    if ($skin === $current['skin']) {
        $config['skins'][$key]['selected'] = TRUE;
    }
}
#
# Default language
#
$langs = SYSTEM::get('languages');
foreach ($langs as $key => $lang) {
    $config['langs'][$key]['lang'] = $lang;
    if ($lang === $current['lang']) {
        $config['langs'][$key]['selected'] = TRUE;
    }
}
#
# Default captcha
#
$captcha = ['Original', 'Color ', 'Random'];
foreach ($captcha as $key => $title) {
    $config['captchas'][$key]['captcha'] = $title;
    if ($title === $current['captcha']) {
        $config['captchas'][$key]['selected'] = TRUE;
    }
}
#
# Default timezone
#
foreach ($LANG['tz'] as $tz => $title) {
    $config['tzs'][$tz]['tz']    = $tz;
    $config['tzs'][$tz]['title'] = $title;
    if ($tz == $current['tz']) {
        $config['tzs'][$tz]['selected'] = TRUE;
    }
}

$config['welcome_msg'] = file_exists(CONTENT.'intro') ? file_get_contents(CONTENT.'intro') : '';
#
# Defualt settings for music player
#
$config['bgcolor']        = GetColor('bgcolor',        strtr($current['bgcolor'],        ['0x' => '#']));
$config['leftbg']         = GetColor('leftbg',         strtr($current['leftbg'],         ['0x' => '#']));
$config['lefticon']       = GetColor('lefticon',       strtr($current['lefticon'],       ['0x' => '#']));
$config['rightbg']        = GetColor('rightbg',        strtr($current['rightbg'],        ['0x' => '#']));
$config['righticon']      = GetColor('righticon',      strtr($current['righticon'],      ['0x' => '#']));
$config['rightbghover']   = GetColor('rightbghover',   strtr($current['rightbghover'],   ['0x' => '#']));
$config['righticonhover'] = GetColor('righticonhover', strtr($current['righticonhover'], ['0x' => '#']));
$config['playertext']     = GetColor('playertext',     strtr($current['playertext'],     ['0x' => '#']));
$config['slider']         = GetColor('slider',         strtr($current['slider'],         ['0x' => '#']));
$config['track']          = GetColor('track',          strtr($current['track'],          ['0x' => '#']));
$config['border']         = GetColor('border',         strtr($current['border'],         ['0x' => '#']));
$config['loader']         = GetColor('loader',         strtr($current['loader'],         ['0x' => '#']));

$TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
$TEMPLATE->set($config);
echo $TEMPLATE->parse();
