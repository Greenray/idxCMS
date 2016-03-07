<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module TAGCLOUD

if (!defined('idxCMS')) die();

$tagcloud = CONFIG::getSection('tagcloud');
#
# Tags colors
#
if (!empty($tagcloud['color'])) {
    $tagcloud['color'] = strtr($tagcloud['color'], ['#' => '0x']);

    for ($i = 0; $i < 11; $i++) {
        $colors[$i] = $tagcloud['color'];
    }

} else $colors = ['0xff0000','0x0000ff','0x00ff00','0xffff00','0xff00ff','0xff9900',
                  '0x808080','0x993300','0x00ffff','0x0f0f0f','0x6699ff'];
#
# Colors for highlighting tags
#
if (!empty($tagcloud['hicolor'])) {
    $tagcloud['hicolor'] = strtr($tagcloud['hicolor'], ['#' => '0x']);

    for ($i = 0; $i < 11; $i++) {
        $hicolors[$i] = $tagcloud['hicolor'];
    }

} else $hicolors = ['0xff9900','0x808080','0x0000ff','0x00ff00','0xffff00',
                    '0x6699ff','0xff00ff','0x00ffff','0x993300','0xff0000','0x0f0f0f'];

$search     = '';
$search_txt = '';
$enabled = CONFIG::getSection('enabled');
$modules = ['posts', 'forum', 'catalogs', 'gallery'];

foreach ($modules as $allowed) {
    if (array_key_exists($allowed, $enabled)) {
        $search     .= '%26'.$allowed.'=on';      # Create the parameter for search from flash tagcloud
        $search_txt .= '&'.$allowed.'=on';    # Create the parameter for search from text tagcloud
    }
}

if (empty($tagcloud['wmode']))
     $tagcloud['wmode']   = '';
else $tagcloud['bgcolor'] = '';

$tags = PrepareTags();

if (!empty($tags)) {
    #
    # Number of tags to show in tagcloud
    #
    $tags_amount = ($tagcloud['tags'] < sizeof($tags)) ? $tagcloud['tags'] : sizeof($tags);
    $tags = array_slice($tags, 0, $tags_amount, TRUE);
    uasort($tags, 'scmp');
    $tags_cloud = [];
    $tags_rate  = [];

    foreach ($tags as $key => $value) {
        $tags_cloud[] = $key;
        $tags_rate[]  = $value;
    }
    #
    # Prepear links for flash cloud
    #
    $tagcloud['tagcloud'] = '<tags>';

    for ($i = 0; $i < $tags_amount; $i++) {
        $tagcloud['tagcloud'] .= '<a href=\''.MODULE.'search%26search='.$tags_cloud[$i].$search.'\' style=\''.$tagcloud['font'].'\' color=\''.$colors[mt_rand(0, 10)].'\' hicolor=\''.$hicolors[mt_rand(0, 10)].'\'>'.$tags_cloud[$i].'</a>';
    }

    $tagcloud['tagcloud'] .= '</tags>';
    #
    # Prepear links for text cloud
    #
    $colors = ['#ff0000','#0000ff','#00ff00','#ffff00','#ff00ff','#ff9900',
               '#808080','#993300','#00ffff','#0f0f0f','#6699ff'];
    $font_size = ['8','9','10','11','12','13','14','15','16','18'];

    $tagcloud['tags_txt'] = '';

    for ($i = 0; $i < $tags_amount; $i++) {

        if ($tags_rate[$i] > 9) {
            $tags_rate[$i] = 9;
        }
        $tagcloud['tags_txt'] .= '<span><a href="'.MODULE.'search&search='.$tags_cloud[$i].$search_txt.'" style="color:'.$colors[mt_rand(0,10)].';font-size:'.$font_size[$tags_rate[$i]].'px">'.$tags_cloud[$i].'</a></span>'.LF;
    }

    $tagcloud['path'] = MODULES.'tagcloud'.DS;

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'tagcloud.tpl');
    $TEMPLATE->set($tagcloud);
    SYSTEM::defineWindow('Tagcloud', $TEMPLATE->parse());
}
