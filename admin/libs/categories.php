<?php
# idxCMS Flat Files Content Management Sysytem v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Categories management.

if (!defined('idxADMIN')) die();

if (!empty($REQUEST['posts'])) {
    header('Location: '.MODULE.'admin&id=posts.items');
    die();
}

$obj = strtoupper($module);
$sections = CMS::call($obj)->getSections();

try {
    if (!empty($REQUEST['p'])) {
        SaveSortedSections($obj, $REQUEST['p']);

    } elseif (!empty($REQUEST['save'])) {
        $categories = CMS::call($obj)->getCategories($REQUEST['section']);
        CMS::call($obj)->saveCategory();

    } else {
        if (!empty($REQUEST['delete'])) {
            $category   = explode('.', $REQUEST['delete']);
            $categories = CMS::call($obj)->getCategories($category[0]);
            CMS::call($obj)->removeCategory($category[1]);
        }
    }
} catch (Exception $error) {
    ShowError($error->getMessage());
}
#
# Initialize again because of sorted sections
#
$sections = CMS::call($obj)->getSections();
$output   = [];
$TPL = new TEMPLATE(__DIR__.DS.'categories.tpl');

if ($obj === 'POSTS') {
    #
    # Existing categories
    # We can't delete or move system section, so exlcude it from sorting
    #
    $output['system']['drafts'] = $sections['drafts'];
    $output['system']['drafts']['categories'] = CMS::call('POSTS')->getCategories('drafts');

    if (USER::$root) {
        $output['system']['drafts']['categories'][1]['desc']  = CMS::call('PARSER')->parseText($output['system']['drafts']['categories'][1]['desc']);
        $output['system']['drafts']['categories'][1]['class'] = 'light';
        $content = CMS::call('POSTS')->getContent(1);
        $output['system']['drafts']['categories'][1]['items'] = !empty($content) ? count($content) : 0;

    } else unset($output['system']['drafts']['categories'][1]);

    $output['system']['drafts']['categories'][2]['desc']  = CMS::call('PARSER')->parseText($output['system']['drafts']['categories'][2]['desc']);
    $output['system']['drafts']['categories'][2]['class'] = 'dark';
    $content = CMS::call('POSTS')->getContent(2);
    $output['system']['drafts']['categories'][2]['items'] = !empty($content) ? count($content) : 0;

    unset($sections['drafts']);

    $TPL->set($output);
    $output = [];
}

if (!empty($sections)) {
    $choice = [];
    foreach ($sections as $id => $section) {
        $output[$id]['id']    = $choice[$id]['id']    = $id;
        $output[$id]['title'] = $choice[$id]['title'] = $section['title'];
        $categories = CMS::call($obj)->getCategories($id);
        if (!empty($categories)) {
            $output[$id]['categories'] = $categories;
            $class = 'light';
            foreach ($categories as $key => $category) {
                $output[$id]['categories'][$key]['desc']  = CMS::call('PARSER')->parseText($category['desc']);
                $content = CMS::call($obj)->getContent($key);
                $output[$id]['categories'][$key]['items'] = !empty($content) ? count($content) : 0;
                $output[$id]['categories'][$key]['class'] = $class;
                $class = ($class === 'light') ? 'dark' : 'light';
            }
        } else $output[$id]['categories'] = [];
    }
    #
    # Show existing sections and categories
    #
    $TPL->set('module',   $module);
    $TPL->set('sections', $output);
    echo $TPL->parse();

    if (!empty($REQUEST['edit'])) {
        #
        # Edit category
        #
        $param    = explode('.', $REQUEST['edit']);
        $section  = CMS::call($obj)->getSection($param[0]);
        $category = CMS::call($obj)->getCategory($param[1]);
        if (!empty($category)) {
            $category['header']  = __('Edit');
            $category['section'] = [$section];
            $category['title']   = empty($REQUEST['title'])  ? $category['title']  : $REQUEST['title'];
            $category['desc']    = empty($REQUEST['desc'])   ? $category['desc']   : $REQUEST['desc'];
            $category['access']  = empty($REQUEST['access']) ? $category['access'] : $REQUEST['access'];
            $category['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.desc');

            $TPL = new TEMPLATE(__DIR__.DS.'category.tpl');
            $TPL->set($category);
            echo $TPL->parse();

        } else {
            header('Location: '.MODULE.'admin&id='.$module.'.categories');
            die();
        }
    } else {
        #
        # Create new category
        #
        if (!empty($REQUEST['new'])) {
            $TPL = new TEMPLATE(__DIR__.DS.'category.tpl');
            $TPL->set([
                'header'   => __('New category'),
                'sections' => $choice,
                'title'    => empty($REQUEST['title'])  ? '' : $REQUEST['title'],
                'desc'     => empty($REQUEST['desc'])   ? '' : $REQUEST['desc'],
                'access'   => empty($REQUEST['access']) ? 0  : $REQUEST['access'],
                'bbCodes'  => CMS::call('PARSER')->showBbcodesPanel('form.desc')
            ]);
            echo $TPL->parse();
        }
    }
} else {
    header('Location: '.MODULE.'admin&id='.$module.'.sections');
    die();
}
