<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Sections management.

if (!defined('idxADMIN')) die();

$obj = strtoupper($module);
$sections = CMS::call($obj)->getSections();

try {
    if (!empty($REQUEST['action']) && !empty($REQUEST['ids'])) {
        if ($obj === 'POSTS') {
            $new = ['drafts' => 'drafts'];
            $ids = array_combine($REQUEST['ids'], $REQUEST['ids']);
            $sec = [];
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
    echo SYSTEM::showError($error->getMessage());
}
#
# Existing sections
#
$sections = CMS::call($obj)->getSections();
$output   = [];

if ($obj === 'POSTS') {
    #
    # We can't move or delete section drafts and exclude it from sorting
    #
    $output['system'][0] = $sections['drafts'];
    $output['system'][0]['desc'] = CMS::call('PARSER')->parseText($sections['drafts']['desc']);
    unset($sections['drafts']);
}

if (!empty($sections)) {
    $class  = 'dark';
    $output['sections'] = $sections;
    foreach ($sections as $id => $section) {
        $output['sections'][$id]['desc'] = CMS::call('PARSER')->parseText($section['desc']);
        $categories = CMS::call($obj)->getCategories($id);
        if (empty($categories)) {
            $output['sections'][$id]['delete'] = TRUE;      # If section is not empty we can't delete it
        }
        $output['sections'][$id]['class'] = $class;
        $class = ($class === 'light') ? 'dark' : 'light';
    }

    $TPL = new TEMPLATE(__DIR__.DS.'sections.tpl');
    $TPL->set($output);
    echo $TPL->parse();
}

if (!empty($REQUEST['edit'])) {
    #
    # Edit section
    #
    $section = $sections[$REQUEST['edit']];
    $section['header']  = __('Edit');
    $section['bbCodes'] = CMS::call('PARSER')->showBbcodesPanel('form.desc');

    $TPL = new TEMPLATE(__DIR__.DS.'section.tpl');
    $TPL->set($section);
    echo $TPL->parse();
}

if (!empty($REQUEST['new']) || empty($sections)) {
    #
    # Create new section
    #
    $TPL = new TEMPLATE(__DIR__.DS.'section.tpl');
    $TPL->set([
        'header'  => __('New section'),
        'section' => empty($REQUEST['section']) ? '' : $REQUEST['section'],
        'title'   => empty($REQUEST['title'])   ? '' : $REQUEST['title'],
        'desc'    => empty($REQUEST['desc'])    ? '' : $REQUEST['desc'],
        'access'  => empty($REQUEST['access'])  ? 0  : $REQUEST['access'],
        'bbCodes' => CMS::call('PARSER')->showBbcodesPanel('form.desc')
    ]);

    echo $TPL->parse();
}
