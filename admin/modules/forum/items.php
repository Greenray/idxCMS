<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FORUM - TOPICS

if (!defined('idxADMIN')) die();

$section  = FILTER::get('REQUEST', 'section');;
$category = FILTER::get('REQUEST', 'category');
$topic    = FILTER::get('REQUEST', 'edit');
$sections   = CMS::call('FORUM')->getSections();
$categories = CMS::call('FORUM')->getCategories($section);
$content    = CMS::call('FORUM')->getContent($category);

# Save new or edited post
if (!empty($REQUEST['save'])) {
    # Check if admin decided to move post
    if (($section !== $REQUEST['new_section']) || ($category !== $REQUEST['new_category'])) {
        if (!empty($REQUEST['item']))
             # Topic exists, so move it
             $topic = CMS::call('FORUM')->moveItem($REQUEST['item'], $REQUEST['new_section'], $REQUEST['new_category']);
        else $topic = '';     # Nothing to move, so add new
        $section  = $REQUEST['new_section'];
        $category = $REQUEST['new_category'];
    } else $topic = FILTER::get('REQUEST', 'item');

    $categories = CMS::call('FORUM')->getCategories($section);
    $content    = CMS::call('FORUM')->getContent($category);
    try {
        CMS::call('FORUM')->saveTopic($topic);
        USER::changeProfileField(USER::getUser('username'), 'topics', '+');
        $topic = '';
    } catch (Exception $error) {
         ShowMessage(__($error->getMessage()));
    }
} elseif (!empty($REQUEST['close']) || !empty($REQUEST['open'])) {
    CMS::call('FORUM')->setValue(
        empty($REQUEST['close']) ? $REQUEST['open'] : $REQUEST['close'],
        'opened',
        empty($REQUEST['close']) ? TRUE : FALSE
    );
} else {
    if (!empty($REQUEST['delete'])) {
        try {
            CMS::call('FORUM')->removeItem($REQUEST['delete']);
        } catch (Exception $error) {
            ShowMessage(__($error->getMessage()));
        }
    }
}

if (!empty($REQUEST['new']) || !empty($topic)) {
    $output = array();
    $choice = array();
    $list_i = array();
    $list_t = array();
    foreach ($sections as $id => $data) {
        $choice[$id]['id']    = $data['id'];
        $choice[$id]['title'] = $data['title'];
        $categories = CMS::call('FORUM')->getCategories($id);
        $ids    = array();
        $titles = array();
        foreach ($categories as $key => $cat) {
            $ids[$id][]    = $key;
            $titles[$id][] = $cat['title'];
        }
        if (!empty($ids) && !empty($titles)) {
            $list_i[] = '"'.implode(',', $ids[$id]).'"';
            $list_t[] = '"'.implode(',', $titles[$id]).'"';
        }
    }
    $output['ids']      = implode(',', $list_i);
    $output['titles']   = implode(',', $list_t);
    $output['sections'] = $choice;
    $output['section_id']     = $section;
    $output['section_title']  = $sections[$section]['title'];
    $categories = CMS::call('FORUM')->getCategories($section);
    $output['categories']     = $categories;
    $output['category_id']    = $category;
    $output['category_title'] = $categories[$category]['title'];
    if (!empty($topic)) {
        $topic = CMS::call('FORUM')->getItem($topic, 'text', FALSE);
        $output['topic']  = $topic['id'];
        $output['title']  = empty($REQUEST['title'])  ? $topic['title']  : $REQUEST['title'];
        $output['text']   = empty($REQUEST['text'])   ? $topic['text']   : $REQUEST['text'];
        $output['opened'] = empty($REQUEST['opened']) ? $topic['opened'] : $REQUEST['opened'];
        $output['pinned'] = empty($REQUEST['pinned']) ? $topic['pinned'] : $REQUEST['pinned'];
    } else {
        $output['topic']  = '';
        $output['title']  = FILTER::get('REQUEST', 'title');
        $output['text']   = FILTER::get('REQUEST', 'text');
        $output['opened'] = empty($REQUEST['opened']) ? TRUE  : $REQUEST['opened'];
        $output['pinned'] = empty($REQUEST['pinned']) ? FALSE : $REQUEST['pinned'];
    }
    $output['sections'][$output['section_id']]['selected']    = TRUE;
    $output['categories'][$output['category_id']]['selected'] = TRUE;
    $output['bbCodes_text'] = ShowBbcodesPanel('topic.text');
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'topic.tpl');
    echo $TPL->parse($output);

} elseif (!empty($sections[$section])) {
    $categories = CMS::call('FORUM')->getCategories($section);
    if (!empty($categories[$category])) {
        $output = array();
        $output['section_id']     = $section;
        $output['section_title']  = $sections[$section]['title'];
        $output['category_id']    = $category;
        $output['category_title'] = $categories[$category]['title'];
        $content = CMS::call('FORUM')->getContent($category);
        foreach ($content as $key => $topic) {
            $topic['date'] = FormatTime('d m Y', $topic['time']);
            if ($topic['opened']) {
                $topic['command'] = __('Close');
                $topic['action']  = 'close';
            } else {
                $topic['command'] = __('Open');
                $topic['action']  = 'open';
            }
            $output['items'][] = $topic;
        }
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'items.tpl');
        echo $TPL->parse($output);

    } else {
        header('Location: '.MODULE.'admin&id=forum.categories');
        die();
    }
} else {
    header('Location: '.MODULE.'admin&id=forum.categories');
    die();
}
?>