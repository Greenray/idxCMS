<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - SECTIONS

if (!defined('idxADMIN')) die();

$obj = strtoupper($module);
$sections = CMS::call($obj)->getSections();

try {
    if (!empty($REQUEST['action']) && !empty($REQUEST['ids'])) {
        CMS::call($obj)->saveSections($REQUEST['ids']);
    } elseif (!empty($REQUEST['save'])) {
        CMS::call($obj)->saveSection();
    } else {
        if (!empty($REQUEST['delete'])) {
            CMS::call($obj)->removeSection($REQUEST['delete']);
        }
    }
} catch (Exception $error) {
    ShowMessage(__($error->getMessage()));
}

# Existing sections
$sections = CMS::call($obj)->getSections();

if (!empty($sections)) {
    $class  = 'even';
    $output = array();
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $output['sections'][$id]['desc'] = ParseText($section['desc']);
        $categories = CMS::call($obj)->getCategories($id);
        if (empty($categories)) {
            $output['sections'][$id]['delete'] = TRUE;      # If section is not empty we can't delete it
        }
        $output['sections'][$id]['class'] = $class;
        $class = ($class === 'odd') ? 'even' : 'odd';
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'sections.tpl');
    echo $TPL->parse($output);
}

if (!empty($REQUEST['edit'])) {
    $section = $sections[$REQUEST['edit']];
    $section['bbCodes'] = ShowBbcodesPanel('form.desc');
    $section['header']  = __('Edit');
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
    echo $TPL->parse($section);
} else {
    if (!empty($REQUEST['new']) || empty($sections)) {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
        echo $TPL->parse(
            array(
                'section' => FILTER::get('REQUEST', 'section'),
                'title'   => FILTER::get('REQUEST', 'title'),
                'desc'    => FILTER::get('REQUEST', 'desc'),
                'access'  => (int) FILTER::get('REQUEST', 'access'),
                'bbCodes' => ShowBbcodesPanel('form.desc'),
                'header'  => __('New section')
            )
        );
    }
}
?>