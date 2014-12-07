<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - SECTIONS

if (!defined('idxADMIN')) die();

$obj = strtoupper($module);
$sections = CMS::call($obj)->getSections();

try {
    if (!empty($REQUEST['action']) && !empty($REQUEST['ids'])) {
        if ($obj === 'POSTS') {
            $new = array('drafts' => 'drafts');
            $ids = array_combine($REQUEST['ids'], $REQUEST['ids']);
            $sec = array();
            $dat = $new + $ids;
            foreach($dat as $key => $value) {
                foreach($sections as $key1 => $value1) {
                    if ($key === $key1) {
                        $sec[] = $value;
                    }
                }
            }
            CMS::call('POSTS')->saveSections($sec);
        } else {
            CMS::call($obj)->saveSections($REQUEST['ids']);
        }
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

if ($obj === 'POSTS') {
    # We can't move or delete section drafts and exclude it from sorting
    $output = array();
    $output['drafts'] = $sections['drafts'];
    $output['drafts']['desc'] = CMS::call('PARSER')->parseText($sections['drafts']['desc']);
    unset($sections['drafts']);
}

if (!empty($sections)) {
    $class  = 'even';
    $output = array();
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $output['sections'][$id]['desc'] = CMS::call('PARSER')->parseText($section['desc']);
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
    $section['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.desc');
    $section['header']  = __('Edit');
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
    echo $TPL->parse($section);
}

if (!empty($REQUEST['new']) || empty($sections)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'section.tpl');
    echo $TPL->parse(
        array(
            'section' => $REQUEST['section'],
            'title'   => $REQUEST['title'],
            'desc'    => $REQUEST['desc'],
            'access'  => (int) $REQUEST['access'],
            'bbCodes' => CMS::call('PARSER')->showBbcodesPanel('form.desc'),
            'header'  => __('New section')
        )
    );
}
