<?php
# idxCMS version 2.2 - Flat Files Content Management System
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POSTS - SECTIONS

if (!defined('idxADMIN')) die();

$sections = CMS::call('POSTS')->getSections();

try {
    if (!empty($REQUEST['action'])) {
        $new = array();
        $new['drafts'] = $sections['drafts'];
        if (!empty($REQUEST['ids'])) {
            $new = array_merge($new, array_combine($REQUEST['ids'], $REQUEST['ids']));
        }
        CMS::call('POSTS')->saveSections($new);
    } elseif (!empty($REQUEST['save'])) {
        CMS::call('POSTS')->saveSection();
    } else {
        if (!empty($REQUEST['delete'])) {
            CMS::call('POSTS')->removeSection($REQUEST['delete']);
        }
    }
} catch (Exception $error) {
    ShowMessage(__($error->getMessage()));
}

# Existing sections
$sections = CMS::call('POSTS')->getSections();
# We can't delete system section and excude it from sorting
$output = array();
$output['drafts'] = $sections['drafts'];
$output['drafts']['desc'] = ParseText($sections['drafts']['desc']);
unset($sections['drafts']);

if (!empty($sections)) {
    $class = 'odd';
    foreach ($sections as $id => $section) {
        # If section is not empty we can't delete it
        $output['sections'][$id] = $section;
        $output['sections'][$id]['desc'] = ParseText($section['desc']);
        $categories = CMS::call('POSTS')->getCategories($id);
        if (empty($categories)) {
            $output['sections'][$id]['delete'] = TRUE;
        }
        $output['sections'][$id]['class'] = $class;
        $class = ($class === 'odd') ? 'even' : 'odd';
    }
}
$TPL = new TEMPLATE(dirname(__FILE__).DS.'sections.tpl');
echo $TPL->parse($output);

if (!empty($REQUEST['new'])) {
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

if (!empty($REQUEST['edit'])) {
    $section = CMS::call('POSTS')->getSection($REQUEST['edit']);
    $section['bbCodes'] = ShowBbcodesPanel('form.desc');
    $section['header']  = __('Edit');
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
    echo $TPL->parse($section);
}
?>