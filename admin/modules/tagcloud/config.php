<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - TAGCLOUD - CONFIGURATION

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

# Keywords array creation
function GetKeywords($words, $config, &$target) {
    $keywords = explode(',', $words);   # Keywords are written through a comma
    foreach ($keywords as $k => $value) {
        $value = trim($value);             # Let's bite off superfluous blanks
        # Check for the resolved length of a keyword
        if ((mb_strlen($value) >= $config['query-min']) && (mb_strlen($value) <= $config['query-max'])) {
            if (!empty($target[$value])) {
                $target[$value]++;        # Existing tag
            } else {
                $target[$value] = 1;      # New tag
            }
        }
    }
}

function CreateTags($posts = TRUE, $files = FALSE) {
    $config   = CONFIG::getSection('search');
    $tags     = [];
    $keywords = CONFIG::getValue('main', 'keywords');
    GetKeywords($keywords, $config, $tags);
    $modules = array('posts','forum','catalogs','galleries');
    $enabled = CONFIG::getSection('enabled');
    foreach($modules as $module) {
        if (array_key_exists($module, $enabled)) {
            $obj = strtoupper($module);
            $sections = CMS::call($obj)->getSections();
            if (!empty($sections['drafts'])) {
                unset($sections['drafts']);
            }
            foreach ($sections as $id => $section) {
                $categories = CMS::call($obj)->getCategories($id);
                foreach ($categories as $key => $category) {
                    $content = CMS::call($obj)->getContent($key);
                    foreach ($content as $i => $post) {
                        if (!empty($post['keywords'])) {
                            GetKeywords($post['keywords'], $config, $tags);
                        }
                    }
                }
            }
        }
    }
    return file_put_contents(CONTENT.'tags', serialize($tags));
}

$config = CONFIG::getSection('tagcloud');

if (isset($init)) {
    if (empty($config)) {
        $config['width']   = 210;
        $config['height']  = 200;
        $config['bgcolor'] = '#FFFFFF';
        $config['color']   = '';
        $config['hicolor'] = '';
        $config['wmode']   = 'transparent';
        $config['speed']   = 100;
        $config['style']   = '16';
        $config['tags']    = '20';
        $config['distr']   = 'true';
        CMS::call('CONFIG')->setSection('tagcloud', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save file');
        }
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['width']   = empty($REQUEST['width'])   ? 210 : (int) $REQUEST['width'];
        $config['height']  = empty($REQUEST['height'])  ? 200 : (int) $REQUEST['height'];
        $config['bgcolor'] = empty($REQUEST['bgcolor']) ? '#FFFFFF' : $REQUEST['bgcolor'];
        $config['color']   = FILTER::get('REQUEST', 'color');
        $config['hicolor'] = FILTER::get('REQUEST', 'hicolor');
        $config['wmode']   = empty($REQUEST['wmode'])   ? ''  : 'transparent';
        $config['speed']   = empty($REQUEST['speed'])   ? 100 : (int) $REQUEST['speed'];
        $config['style']   = empty($REQUEST['style'])   ? 16  : (int) $REQUEST['style'];
        $config['tags']    = empty($REQUEST['tags'])    ? 20  : (int) $REQUEST['tags'];
        $config['distr']   = empty($REQUEST['distr'])   ? 'false' : 'true';
        CMS::call('CONFIG')->setSection('tagcloud', $config);
        if (!CMS::call('CONFIG')->save()) {
            ShowMessage('Cannot save file');
        }
    }
    if (!empty($REQUEST['create'])) {
        /*
        * @todo Make selection
        */
        if (CONFIG::getValue('enabled', 'files')) {
             $result = CreateTags(TRUE, TRUE);
        } else {
            $result = CreateTags(TRUE);
        }
        if ($result === FALSE) {
            ShowMessage('Cannot save file');
        }
    }
    if ($config['distr'] === 'false') {
        unset($config['distr']);
    }
    $search_ini = CONFIG::getSection('search');
    if (!empty($REQUEST['edit'])) {
        $tags = [];
        foreach ($REQUEST['key'] as $key => $tag) {
            if ($tag != '') {
                $tag = $tag;
                # Check for the resolved length of a tag
                if ((mb_strlen($tag) >= $search_ini['query-min']) && (mb_strlen($tag) <= $search_ini['query-max'])) {
                    $tags[$tag] = $REQUEST['value'][$key];     # New tag
                }
            }
        }
        if (!file_put_contents(CONTENT.'tags', serialize($tags))) {
            ShowMessage('Cannot save file');
        }
    }
    $create_tags = 0;
    $config['bgcolor'] = GetColor("bgcolor", $config['bgcolor']);
    $config['color']   = GetColor("color",$config['color']);
    $config['hicolor'] = GetColor("hicolor", $config['hicolor']);
    $tags = PrepareTags();
    $tags_amount = sizeof($tags);
    if ($config['tags'] < $tags_amount) {
        $tags_amount = $config['tags'];
    }
    $config['used']   = array_slice($tags, 0, $tags_amount, TRUE);
    $config['unused'] = array_slice($tags, $tags_amount, -1, TRUE);
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'config.tpl');
    echo $TPL->parse($config);
}
?>