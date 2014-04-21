<?php
# idxCMS version 1.01
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE CATALOGS - RANDOM IMAGE

if (!defined('idxCMS')) die();

$sections = CMS::call('GALLERIES')->getSections();
$section  = array_slice($sections, rand(0, sizeof($sections) - 1), 1);
$albums   = CMS::call('GALLERIES')->getCategories(key($section));

if (!empty($albums)) {
    $output = array();
    $random = (int) CONFIG::getValue('galleries', 'random');
    $count  = sizeof($albums);
    if ($random >= $count) {
        foreach ($albums as $id => $album) {
            $image = CMS::call('GALLERIES')->getRandomImage($id);
            if (!empty($image)) {
                $output['random'][$id] = $image;
                $output['random'][$id]['path'] = $album['path'];
            }
        }
    } else {
        for ($i = 1; $i <= $random; $i++) {
            $album = mt_rand(1, $count);
            $image = CMS::call('GALLERIES')->getRandomImage($album);
            if (!empty($image)) {
                $output['random'][$i] = $image;
                $output['random'][$i]['path'] = $albums[$album]['path'];
            }
        }
    }
    if (!empty($output)) {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'randimage.tpl');
        ShowWindow(__('Random image'), $TPL->parse($output));
    }
}
?>