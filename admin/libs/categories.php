<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

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
    ShowMessage(__($error->getMessage()));
}

# Initialize again because of sorted sections
$sections = CMS::call($obj)->getSections();

if (!empty($sections)) {
    $choice = [];
    $output = [];
    $output['module']   = $module;
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $choice[$id]['id']    = $id;
        $choice[$id]['title'] = $section['title'];
        $categories = CMS::call($obj)->getCategories($id);
        if (!empty($categories)) {
            $class = 'odd';
            foreach ($categories as $key => $category) {
                $output['sections'][$id]['categories'][$key]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
                $content = CMS::call($obj)->getContent($key);
                if (empty($content)) {
                     $output['sections'][$id]['categories'][$key]['delete'] = TRUE;      # If category is not empty we can't delete it
                }
                $output['sections'][$id]['categories'][$key]['class'] = $class;
                $class = ($class === 'odd') ? 'even' : 'odd';
            }
        } else $output['sections'][$id]['categories'] = [];
    }

    # Show existing section and categories
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
    echo $TPL->parse($output);

    if (!empty($REQUEST['edit'])) {
        # Edit category
        $param    = explode('.', $REQUEST['edit']);
        $section  = CMS::call($obj)->getSection($param[0]);
        $category = CMS::call($obj)->getCategory($param[1]);
        if (!empty($category)) {
            $category['title']   = empty($REQUEST['title'])  ? $category['title']  : $REQUEST['title'];
            $category['desc']    = empty($REQUEST['desc'])   ? $category['desc']   : $REQUEST['desc'];
            $category['access']  = empty($REQUEST['access']) ? $category['access'] : $REQUEST['access'];
            $category['section'] = $section;
            $category['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.desc');
            $category['header']  = __('Edit');

            $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
            echo $TPL->parse($category);

        } else {
            header('Location: '.MODULE.'admin&id='.$module.'.categories');
            die();
        }
    } else {
        # Create new category
        if (!empty($REQUEST['new'])) {
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
            echo $TPL->parse([
                'title'    => empty($REQUEST['title'])  ? '' : $REQUEST['title'],
                'desc'     => empty($REQUEST['desc'])   ? '' : $REQUEST['desc'],
                'access'   => empty($REQUEST['access']) ? 0  : (int)$REQUEST['access'],
                'sections' => $choice,
                'bbCodes'  => CMS::call('PARSER')->showBbcodesPanel('form.desc'),
                'header'   => __('New category')
            ]);
        }
    }
} else {
    header('Location: '.MODULE.'admin&id='.$module.'.sections');
    die();
}
