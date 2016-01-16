<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module GALLERY: Random image

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERY')->getSections();
$section  = array_slice($sections, rand(0, sizeof($sections) - 1), 1);
$albums   = CMS::call('GALLERY')->getCategories(key($section));

if (!empty($albums)) {
    $output = [];
    $random = (int) CONFIG::getValue('gallery', 'random');
    $count  = sizeof($albums);
    if ($random >= $count) {
        foreach ($albums as $id => $album) {
            $image = CMS::call('GALLERY')->getRandomImage($id);
            if (!empty($image)) {
                $output[$id] = $image;
                $output[$id]['path'] = $album['path'];
            }
        }
    } else {
        for ($i = 1; $i <= $random; $i++) {
            $album = mt_rand(1, $count);
            $image = CMS::call('GALLERY')->getRandomImage($album);
            if (!empty($image)) {
                $output[$i] = $image;
                $output[$i]['path'] = $albums[$album]['path'];
            }
        }
    }
    if (!empty($output)) {
        $TPL = new TEMPLATE(__DIR__.DS.'randimage.tpl');
        $TPL->set('randoms', $output);
        SYSTEM::defineWindow('Random image', $TPL->parse());
    }
}
