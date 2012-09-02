<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - CATEGORIES

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
            $category = explode('.', $REQUEST['delete']);
            $categories = CMS::call($obj)->getCategories($category[0]);
            CMS::call($obj)->removeCategory($category[1]);
        }
    }
} catch (Exception $error) {
    ShowMessage(__($error->getMessage()));
}

$sections = CMS::call($obj)->getSections();
if (!empty($sections)) {
    $choice = array();
    $output = array();
    $output['module'] = $module;
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $choice[$id]['id'] = $id;
        $choice[$id]['title'] = $section['title'];
        $categories = CMS::call($obj)->getCategories($id);
        if (!empty($categories)) {
            $class = 'odd';
            foreach ($categories as $key => $category) {
                $output['sections'][$id]['categories'][$key]['desc'] = ParseText($category['desc']);
                $content = CMS::call($obj)->getContent($key);
                if (empty($content)) {
                     $output['sections'][$id]['categories'][$key]['delete'] = TRUE;      # If category is not empty we can't delete it
                }
                $output['sections'][$id]['categories'][$key]['class'] = $class;
                $class = ($class === 'odd') ? 'even' : 'odd';
            }
        } else $output['sections'][$id]['categories'] = array();
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'categories.tpl');
    echo $TPL->parse($output);      # Existing categories

    if (!empty($REQUEST['edit'])) {
        $param    = explode('.', $REQUEST['edit']);
        $section  = CMS::call($obj)->getSection($param[0]);
        $category = CMS::call($obj)->getCategory($param[1]);
        if (!empty($category)) {
            $category['title']   = empty($REQUEST['title'])  ? $category['title']  : $REQUEST['title'];
            $category['desc']    = empty($REQUEST['desc'])   ? $category['desc']   : $REQUEST['desc'];
            $category['access']  = empty($REQUEST['access']) ? $category['access'] : $REQUEST['access'];
            $category['section'] = $section;
            $category['bbCodes'] = ShowBbcodesPanel('form.desc');
            $category['header']  = __('Edit');
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'category.tpl');
            echo $TPL->parse($category);
        } else {
            header('Location: '.MODULE.'admin&id='.$module.'.categories');
            die();
        }
    } else {
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
    }
} else {
    header('Location: '.MODULE.'admin&id='.$module.'.sections');
    die();
}
?>