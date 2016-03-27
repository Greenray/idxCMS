<?php
/**
 * Tagcloud.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      admin/modules/tagcloud/config.php
 * @package   Tagcloud
 * @overview  Website Tagcloud.
 *            A visual representation of the keywords on your website.
 *            Sold in two formats: flash and text. If the flash plugin is unavailable or disabled, the text version will be automatically included.
 *            Associated with the search module.
 */

if (!defined('idxADMIN') || !USER::$root) die();

/**
 * Keywords array creation.
 *
 * @param  string $words   Comma separated list of keywords
 * @param  array  $config  Tagcloud configuration
 * @param  array  &$target The result array
 * @return array           Tags for tagcloud
 */
function GetKeywords($words, $config, &$target) {
    $keywords = explode(',', $words);      # Keywords are written through a comma
    foreach ($keywords as $k => $value) {
        $value = trim($value);             # Let's bite off superfluous blanks
        #
        # Check for the resolved length of a keyword
        #
        if ((mb_strlen($value) >= $config['query_min']) && (mb_strlen($value) <= $config['query_max'])) {
            if (!empty($target[$value]))
                 $target[$value]++;        # Existing tag.
            else $target[$value] = 1;      # New tag.
        }
    }
}

/**
 * Creates tags for tagcloud.
 *
 * @return boolean
 */
function CreateTags() {
    $config   = CONFIG::getSection('search');
    $keywords = CONFIG::getValue('main', 'keywords');
    $tags     = [];
    GetKeywords($keywords, $config, $tags);
    $modules = ['posts','forum','catalogs','gallery'];
    $enabled = CONFIG::getSection('enabled');
    foreach($modules as $module) {
        if (array_key_exists($module, $enabled)) {
            $obj = strtoupper($module);
            $sections = CMS::call($obj)->getSections();
            if (!empty($sections['drafts'])) unset($sections['drafts']);

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
    return file_put_contents(CONTENT.'tags', json_encode($tags, JSON_UNESCAPED_UNICODE), LOCK_EX);
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
        $config['distr']   = 'TRUE';
        CMS::call('CONFIG')->setSection('tagcloud', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }
} else {
    if (!empty($REQUEST['save'])) {
        $config['width']   = empty($REQUEST['width'])   ? 210       : $REQUEST['width'];
        $config['height']  = empty($REQUEST['height'])  ? 200       : $REQUEST['height'];
        $config['bgcolor'] = empty($REQUEST['bgcolor']) ? '#FFFFFF' : $REQUEST['bgcolor'];
        $config['color']   = FILTER::get('REQUEST', 'color');
        $config['hicolor'] = FILTER::get('REQUEST', 'hicolor');
        $config['wmode']   = empty($REQUEST['wmode'])   ? ''    : 'transparent';
        $config['speed']   = empty($REQUEST['speed'])   ? 100   : $REQUEST['speed'];
        $config['style']   = empty($REQUEST['style'])   ? 16    : $REQUEST['style'];
        $config['tags']    = empty($REQUEST['tags'])    ? 20    : $REQUEST['tags'];
        $config['distr']   = empty($REQUEST['distr'])   ? FALSE : TRUE;
        CMS::call('CONFIG')->setSection('tagcloud', $config);
        if (CMS::call('CONFIG')->save())
             ShowMessage('Configuration has been saved');
        else ShowError('Cannot save file'.' config.ini');
    }
    if (!empty($REQUEST['create'])) {
        $result = CreateTags();
        if (!$result) ShowError('Cannot save file', ' '.CONTENT.'tags');
    }
    if ($config['distr'] === 'FALSE') {
        unset($config['distr']);
    }
    $search_ini = CONFIG::getSection('search');
    if (!empty($REQUEST['edit'])) {
        $tags = [];
        foreach ($REQUEST['key'] as $key => $tag) {
            if ($tag !== '') {
                #
                # Check for the resolved length of a tag
                #
                if ((mb_strlen($tag) >= $search_ini['query_min']) && (mb_strlen($tag) <= $search_ini['query_max'])) {
                    $tags[$tag] = $REQUEST['value'][$key];     # New tag
                }
            }
        }
        if (!file_put_contents(CONTENT.'tags', json_encode($tags, JSON_UNESCAPED_UNICODE), LOCK_EX)) {
            ShowError('Cannot save file'.' '.CONTENT.'tags');
        }
    }
    $create_tags = 0;

    $config['bgcolor'] = GetColor("bgcolor", $config['bgcolor']);
    $config['color']   = GetColor("color",   $config['color']);
    $config['hicolor'] = GetColor("hicolor", $config['hicolor']);

    $tags = PrepareTags();
    $i = 0;
    foreach($tags as $key => $tag) {
        $words[$i]['key'] = $tag;
        $words[$i]['tag'] = $key;
        $i++;
    }

    $tags_amount = $i;
    if ($config['tags'] < $tags_amount) {
        $tags_amount = $config['tags'];
    }
    $config['used']   = array_slice($words, 0, $tags_amount, TRUE);
    $config['unused'] = array_slice($words, $tags_amount, -1, TRUE);

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'config.tpl');
    $TEMPLATE->set($config);
    echo $TEMPLATE->parse();
}
