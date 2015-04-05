<?php
# idxCMS Flat Files Content Management Sysytem
# Module Tagcloud
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$tc = CONFIG::getSection('tagcloud');

# Tags colors
if (!empty($tc['color'])) {
    $tc['color'] = strtr($tc['color'], array("#" => "0x"));
    for ($i = 0; $i < 11; $i++) {
        $colors[$i] = $tc['color'];
    }
} else $colors = array('0xff0000','0x0000ff','0x00ff00','0xffff00','0xff00ff','0xff9900','0x808080','0x993300','0x00ffff','0x0f0f0f','0x6699ff');

# Colors for highlighting tags
if (!empty($tc['hicolor'])) {
    $tc['hicolor'] = strtr($tc['hicolor'], array("#" => "0x"));
    for ($i = 0; $i < 11; $i++) {
        $hicolors[$i] = $tc['hicolor'];
    }
} else $hicolors = array('0xff9900','0x808080','0x0000ff','0x00ff00','0xffff00','0x6699ff','0xff00ff','0x00ffff','0x993300','0xff0000','0x0f0f0f');

$tc['search'] = '';
$tc['search_txt'] = '';
$enabled = CONFIG::getSection('enabled');
$search  = array('posts', 'forum', 'catalogs');

foreach ($search as $allowed) {
    if (array_key_exists($allowed, $enabled)) {
        $tc['search'] .= '%26'.$allowed.'=on';          # Create the parameter for search from flash tagcloud
        $tc['search_txt'] .= '&amp;'.$allowed.'=on';    # Create the parameter for search from text tagcloud
    }
}

if (empty($tc['wmode']))
     $tc['wmode']   = '';
else $tc['bgcolor'] = '';

$tags = PrepareTags();

if (!empty($tags)) {
    $tags_amount = sizeof($tags);
    if ($tc['tags'] < $tags_amount) $tags_amount = $tc['tags'];     # Number of tags to show in tagcloud

    $tags = array_slice($tags, 0, $tags_amount, TRUE);
    uasort($tags, 'scmp');
    $tags_cloud = [];
    $tags_rate  = [];
    foreach ($tags as $key => $value) {
        $tags_cloud[] = $key;
        $tags_rate[]  = $value;
    }
    # Prepear link for flash cloud
    $tc['tagcloud'] = '<tags>';
    for ($i = 0; $i < $tags_amount; $i++) {
        $rand = mt_rand(0, 10);
        $tc['tagcloud'] .= '<a href=\'./?module=search%26search='.$tags_cloud[$i].$tc['search'].'\' style=\''.$tc['style'].'\' color=\''.$colors[$rand].'\' hicolor=\''.$hicolors[$rand].'\'>'.$tags_cloud[$i].'</a>';
    }
    $tc['tagcloud'] .= '</tags>';
    $tc['tags_amount'] = $tags_amount;
    $tc['tags_cloud']  = $tags_cloud;
    $tc['tags_rate']   = $tags_rate;
    $tc['tags_colors'] = array('#ff0000','#0000ff','#00ff00','#ffff00','#ff00ff','#ff9900','#808080','#993300','#00ffff','#0f0f0f','#6699ff');
    $tc['font_size']   = array('8','9','10','11','12','14','16','18','20','22');
    $tc['tags_txt']    = '';
    for ($i = 0; $i < $tc['tags_amount']; $i++) {
        if ($tc['tags_rate'][$i] > 9) {
            $tc['tags_rate'][$i] = 9;
        }
        $tc['tags_txt'] .= '<span><a href=\'./?module=search&amp;search='.$tc['tags_cloud'][$i].$tc['search_txt'].'\' style="color:'.$tc['tags_colors'][mt_rand(0,10)].';font-size:'.$tc['font_size'][$tc['tags_rate'][$i]].'px">'.$tc['tags_cloud'][$i].'</a></span>'.LF;
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'tagcloud.tpl');
    ShowWindow(__('Tagcloud'), $TPL->parse($tc));
}
