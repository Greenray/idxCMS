<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GENERAL CONFIGURATION

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

if (!empty($REQUEST['save'])) {
    $config = array();
    $config['title'] = !empty($REQUEST['title']) ? $REQUEST['title'] : 'idxCMS';
    if (!empty($REQUEST['url'])) {
        $config['url'] = $REQUEST['url'];
        if ($config['url']{strlen($config['url']) - 1} !== DS) {
            $config['url'] .= DS;
        }
    } else $config['url']  = '';
    $config['description'] = !empty($REQUEST['description']) ? $REQUEST['description'] : 'Система управления сайтом idxCMS';
    $config['copyright']   = !empty($REQUEST['copyright'])   ? $REQUEST['copyright']   : 'Greenray 2012';
    $config['slogan']      = !empty($REQUEST['slogan'])      ? $REQUEST['slogan']      : 'Пока не треснешь как следует, ничего не заработает (В.В.Путин)';
    $config['cookie']      = !empty($REQUEST['cookie'])      ? $REQUEST['cookie']      : 'idxCMS';
    if (empty($REQUEST['keywords']))
         $config['keywords'] = 'idxCMS,CMS,opensource,php';
    else $config['keywords'] = preg_replace('/\s/', '', $REQUEST['keywords']);
    $config['index-module']  = empty($REQUEST['index-module']) ? 'default' : $REQUEST['index-module'];
    $config['skin']          = empty($REQUEST['skin'])         ? 'Default' : $REQUEST['skin'];
    $config['lang']          = empty($REQUEST['lang'])         ? 'russian' : $REQUEST['lang'];
    $config['allow-skin']    = empty($REQUEST['allow-skin'])   ? ''        : '1';
    $config['allow-lang']    = empty($REQUEST['allow-lang'])   ? ''        : '1';
    $config['detect-lang']   = empty($REQUEST['detect-lang'])  ? ''        : '1';
    $config['per-page']      = empty($REQUEST['per-page'])     ? 10        : (int) $REQUEST['per-page'];
    $config['last']          = empty($REQUEST['last'])         ? 10        : (int) $REQUEST['last'];
    $max_filesize = ini_get('upload_max_filesize');
    $config['file-max-size']  = empty($REQUEST['file-max-size'])  ? $max_filesize : (int) $REQUEST['file-max-size'];
    $config['image-max-size'] = empty($REQUEST['image-max-size']) ? $max_filesize : (int) $REQUEST['image-max-size'];
    $config['thumb-width']    = empty($REQUEST['thumb-width'])    ? 200       : (int) $REQUEST['thumb-width'];
    $config['thumb-height']   = empty($REQUEST['thumb-height'])   ? 150       : (int) $REQUEST['thumb-height'];
    $config['captcha']        = empty($REQUEST['captcha'])        ? 'Random'  : $REQUEST['captcha'];
    $config['tz']             = empty($REQUEST['tz'])             ? 3         : (int) $REQUEST['tz'];
    $config['welcome']        = empty($REQUEST['welcome'])        ? ''        : '1';
    CMS::call('CONFIG')->setSection('main', $config);
    $config = array();
    $config['query-min']   = empty($REQUEST['query-min'])   ? 6  : (int) $REQUEST['query-min'];
    $config['query-max']   = empty($REQUEST['query-max'])   ? 20 : (int) $REQUEST['query-max'];
    $config['block']       = empty($REQUEST['block'])       ? 50 : (int) $REQUEST['block'];
    $config['per-page']    = empty($REQUEST['per-page'])    ? 20 : (int) $REQUEST['per-page'];
    $config['allow-guest'] = empty($REQUEST['allow-guest']) ? '' : '1';
    CMS::call('CONFIG')->setSection('search', $config);
    $config = array();
    $config['width']          = empty($REQUEST['width'])          ? 290        : (int) $REQUEST['width'];
    $config['height']         = empty($REQUEST['height'])         ? 24         : (int) $REQUEST['height'];
    $config['bgcolor']        = empty($REQUEST['bgcolor'])        ? '0x66ff66' : strtr($REQUEST['bgcolor'],        array("#" => "0x"));
    $config['leftbg']         = empty($REQUEST['leftbg'])         ? '0x969696' : strtr($REQUEST['leftbg'],         array("#" => "0x"));
    $config['lefticon']       = empty($REQUEST['lefticon'])       ? '0x3333ff' : strtr($REQUEST['lefticon'],       array("#" => "0x"));
    $config['rightbg']        = empty($REQUEST['rightbg'])        ? '0x394670' : strtr($REQUEST['rightbg'],        array("#" => "0x"));
    $config['righticon']      = empty($REQUEST['righticon'])      ? '0xffffff' : strtr($REQUEST['righticon'],      array("#" => "0x"));
    $config['rightbghover']   = empty($REQUEST['rightbghover'])   ? '0xff0000' : strtr($REQUEST['rightbghover'],   array("#" => "0x"));
    $config['righticonhover'] = empty($REQUEST['righticonhover']) ? '0xffffff' : strtr($REQUEST['righticonhover'], array("#" => "0x"));
    $config['playertext']     = empty($REQUEST['playertext'])     ? '0x000000' : strtr($REQUEST['playertext'],     array("#" => "0x"));
    $config['slider']         = empty($REQUEST['slider'])         ? '0xff0000' : strtr($REQUEST['slider'],         array("#" => "0x"));
    $config['track']          = empty($REQUEST['track'])          ? '0xffffff' : strtr($REQUEST['track'],          array("#" => "0x"));
    $config['border']         = empty($REQUEST['border'])         ? '0x666666' : strtr($REQUEST['border'],         array("#" => "0x"));
    $config['loader']         = empty($REQUEST['loader'])         ? '0xffffff' : strtr($REQUEST['loader'],         array("#" => "0x"));
    $config['autostart']      = empty($REQUEST['autostart'])      ? '' : '1';
    $config['loop']           = empty($REQUEST['loop'])           ? '' : '1';
    CMS::call('CONFIG')->setSection('audio', $config);
    $config = array();
    $config['width']  = empty($REQUEST['width'])  ? 435 : (int) $REQUEST['width'];
    $config['height'] = empty($REQUEST['height']) ? 350 : (int) $REQUEST['height'];
    CMS::call('CONFIG')->setSection('video', $config);
    if (!CMS::call('CONFIG')->save()) {
        ShowMessage('Cannot save configuration');
    }
}
if (!empty($REQUEST['meta_tags'])) {
    if (!file_put_contents(CONTENT.'meta', $REQUEST['meta_tags'])) {
        ShowMessage('Cannot save', 'meta');
    }
}
if (!empty($REQUEST['welcome_msg'])) {
    if (!file_put_contents(CONTENT.'intro', ParseText($REQUEST['welcome_msg']))) {
        ShowMessage('Cannot save', 'intro');
    }
}

# Main configuration editing
$config  = CONFIG::getSection('main');
$config += CONFIG::getSection('search');
$config += CONFIG::getSection('audio');
$config += CONFIG::getSection('video');
$config['max_filesize'] = ini_get('upload_max_filesize');
/*
 * @todo Automatic creation of list of available modules
 */
$modules = array();
$modules['default']  = __('Index');
$modules['posts']    = __('Posts');
$modules['forum']    = __('Forum');
$modules['catalogs'] = __('Catalogs');
$modules['news']     = __('Last news');
$modules['sitemap']  = __('Sitemap');
$available_modules = array();
foreach ($modules as $module => $title) {
    $available_modules[$module]['module'] = $module;
    $available_modules[$module]['title']  = $title;
    if ($module === $config['index-module']) {
        $available_modules[$module]['selected'] = TRUE;
    }
}
$config['modules'] = $available_modules;
$skins = AdvScanDir(SKINS, '', 'dir', FALSE, array('bbcodes', 'forum', 'icons', 'images', 'smiles'));
$available_skins = array();
foreach ($skins as $i => $skin) {
    $available_skins[$i]['skin'] = $skin;
    if ($skin === $config['skin']) {
        $available_skins[$i]['selected'] = TRUE;
    }
}
$config['skins'] = $available_skins;
$langs = SYSTEM::get('languages');
$available_langs = array();
foreach ($langs as $i => $lang) {
    $available_langs[$i]['lang'] = $lang;
    if ($lang === $config['lang']) {
        $available_langs[$i]['selected'] = TRUE;
    }
}
$config['langs'] = $available_langs;
$captcha = array(
    'Original' => 'Original',
    'Color'    => 'Color ',
    'Random'   => 'Random'
);
$available_captcha = array();
foreach ($captcha as $i => $type) {
    $available_captcha[$i]['captcha'] = $type;
    if ($type === $config['captcha']) {
        $available_captcha[$i]['selected'] = TRUE;
    }
}
$config['captcha'] = $available_captcha;
$config['meta_tags'] = file_get_contents(CONTENT.'meta');
$available_tz = array();
foreach ($LANG['tz'] as $tz => $title) {
    $available_tz[$tz]['tz'] = $tz;
    $available_tz[$tz]['title'] = $title;
    if ($tz === (int) $config['tz']) {
        $available_tz[$tz]['selected'] = TRUE;
    }
}
$config['tz'] = $available_tz;
$config['welcome_msg'] = file_get_contents(CONTENT.'intro');
$config['bgcolor']        = GetColor('bgcolor', strtr($config['bgcolor'], array("0x" => "#")));
$config['leftbg']         = GetColor('leftbg', strtr($config['leftbg'], array("0x" => "#")));
$config['lefticon']       = GetColor('lefticon', strtr($config['lefticon'], array("0x" => "#")));
$config['rightbg']        = GetColor('rightbg', strtr($config['rightbg'], array("0x" => "#")));
$config['righticon']      = GetColor('righticon', strtr($config['righticon'], array("0x" => "#")));
$config['rightbghover']   = GetColor('rightbghover', strtr($config['rightbghover'], array("0x" => "#")));
$config['righticonhover'] = GetColor('righticonhover', strtr($config['righticonhover'], array("0x" => "#")));
$config['playertext']     = GetColor('playertext', strtr($config['playertext'], array("0x" => "#")));
$config['slider'] = GetColor('slider', strtr($config['slider'], array("0x" => "#")));
$config['track']  = GetColor('track', strtr($config['track'], array("0x" => "#")));
$config['border'] = GetColor('border', strtr($config['border'], array("0x" => "#")));
$config['loader'] = GetColor('loader', strtr($config['loader'], array("0x" => "#")));

$TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
echo $TPL->parse($config);
?>