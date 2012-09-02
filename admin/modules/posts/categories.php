<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION  POSTS - CATEGORIES

if (!defined('idxADMIN')) die();

if (!empty($REQUEST['posts'])) {
    header('Location: '.MODULE.'admin&id=posts.posts');
    die();
}

$sections = CMS::call('POSTS')->getSections();
try {
    if (!empty($REQUEST['p'])) {
        SaveSortedSections('POSTS', $REQUEST['p']);
    } elseif (!empty($REQUEST['save'])) {
        $categories = CMS::call('POSTS')->getCategories($REQUEST['section']);
        CMS::call('POSTS')->saveCategory();
    } else {
        if (!empty($REQUEST['delete'])) {
            $category = explode('.', $REQUEST['delete']);
            $categories = CMS::call('POSTS')->getCategories($category[0]);
            CMS::call('POSTS')->removeCategory($category[1]);
        }
    }
} catch (Exception $error) {
    ShowMessage(__($error->getMessage()));
}

# Existing categories
# We can't delete or move system section, so exlcude it from sorting
$output = array();
$output['system']['drafts'] = $sections['drafts'];
$output['system']['drafts']['categories'] = CMS::call('POSTS')->getCategories('drafts');

if (CMS::call('USER')->checkRoot()) {
    $output['system']['drafts']['categories'][1]['desc']  = ParseText($output['system']['drafts']['categories'][1]['desc']);
    $output['system']['drafts']['categories'][1]['class'] = 'odd';
    $content = CMS::call('POSTS')->getContent(1);
    if (!empty($content)) $output['system']['drafts']['categories'][1]['posts'] = TRUE;
} else unset($output['system']['drafts']['categories'][1]);

$output['system']['drafts']['categories'][2]['desc']  = ParseText($output['system']['drafts']['categories'][2]['desc']);
$output['system']['drafts']['categories'][2]['class'] = 'even';
$content = CMS::call('POSTS')->getContent(2);
if (!empty($content)) $output['system']['drafts']['categories'][2]['posts'] = TRUE;

unset($sections['drafts']);
$choice = array();
if (!empty($sections)) {
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $choice[$id]['id'] = $id;
        $choice[$id]['title'] = $section['title'];
        $categories = CMS::call('POSTS')->getCategories($id);
        if (!empty($categories)) {
            $output['sections'][$id]['categories'] = $categories;
            $class = 'odd';
            foreach ($categories as $key => $category) {
                $output['sections'][$id]['categories'][$key]['desc'] = ParseText($category['desc']);
                $content = CMS::call('POSTS')->getContent($key);
                if (!empty($content)) {
                     $output['sections'][$id]['categories'][$key]['posts'] = TRUE;  # If category is not empty we can't delete it
                }
                $output['sections'][$id]['categories'][$key]['class'] = $class;
                $class = ($class === 'odd') ? 'even' : 'odd';
            }
        } else $output['sections'][$id]['categories'] = array();
    }
}
$TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
echo $TPL->parse($output);

if (!empty($REQUEST['new'])) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
    echo $TPL->parse(
        array(
            'title'    => FILTER::get('REQUEST', 'title'),
            'desc'     => FILTER::get('REQUEST', 'desc'),
            'access'   => (int) FILTER::get('REQUEST', 'access'),
            'sections' => $choice,
            'bbCodes'  => ShowBbcodesPanel('form.desc'),
            'header'   => __('New category')
        )
    );
}
# Edit category
if (!empty($REQUEST['edit'])) {
    $param    = explode('.', $REQUEST['edit']);
    $section  = CMS::call('POSTS')->getSection($param[0]);
    $category = CMS::call('POSTS')->getCategory($param[1]);
    if (!empty($category)) {
        if ($param[0] === 'drafts') {
            $category['path'] = POSTS.'drafts'.DS.$param[1].DS;
        }
        $category['title']   = empty($REQUEST['title'])  ? $category['title']  : $REQUEST['title'];
        $category['desc']    = empty($REQUEST['desc'])   ? $category['desc']   : $REQUEST['desc'];
        $category['access']  = empty($REQUEST['access']) ? $category['access'] : $REQUEST['access'];
        $category['section'] = $section;
        $category['bbCodes'] = ShowBbcodesPanel('form.desc');
        $category['header']  = __('Edit');
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
        echo $TPL->parse($category);
    }
}
?>