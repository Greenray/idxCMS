<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

# Only registered users can post
if (!USER::$logged_in) Redirect('posts');

$sections = CMS::call('POSTS')->getSections();

if (!USER::$root) {
    # User cannot post without Admin's moderation
    $section  = 'drafts';
    $category = 2;
} else {
    $section  = FILTER::get('REQUEST', 'section');
    $category = FILTER::get('REQUEST', 'category');
    if (empty($section))  $section  = 'drafts';
    if (empty($category)) $category = '1';
}

$post = FILTER::get('REQUEST', 'item');

# Save new or edited post
if (!empty($REQUEST['save'])) {
    $new_section  = FILTER::get('REQUEST', 'new_section');
    $new_category = FILTER::get('REQUEST', 'new_category');
    # Check if admin decided to move post
    if (!empty($new_section) && !empty($new_category)) {
        if (($section !== $new_section) || ($category !== $new_category)) {
            if (!empty($post)) {
                # Post exists, so move it
                CMS::call('POSTS')->getCategories($section);
                CMS::call('POSTS')->getContent($category);
                $post = CMS::call('POSTS')->moveItem($post, $new_section, $new_category);
            } else {
                $post = '';     # Nothing to move, so add new
            }
            $section  = $new_section;
            $category = $new_category;
        }
    }
    CMS::call('POSTS')->getCategories($section);
    CMS::call('POSTS')->getContent($category);
    try {
        $post = CMS::call('POSTS')->saveItem($post);
        USER::changeProfileField(USER::getUser('username'), 'posts', '+');
        if ($section === 'drafts') {
            Redirect('posts');
        }
        Redirect('posts', $section, $category, $post);
    } catch (Exception $error) {
        ShowError(__($error->getMessage()));
    }
}

if (USER::$root) {
    $output = [];
    $choice = [];
    $list_i = [];
    $list_t = [];
    foreach ($sections as $id => $data) {
        $categories = CMS::call('POSTS')->getCategories($id);
        if (!empty($categories)) {
            # Don't include sections without categories
            $choice[$id]['id']    = $data['id'];
            $choice[$id]['title'] = $data['title'];
            $ids    = [];
            $titles = [];
            foreach ($categories as $key => $cat) {
                $ids[$id][]    = $key;
                $titles[$id][] = $cat['title'];
            }
            if (!empty($ids) && !empty($titles)) {
                $list_i[] = '"'.implode(',', $ids[$id]).'"';
                $list_t[] = '"'.implode(',', $titles[$id]).'"';
            }
        }
    }
    $output['ids']      = implode(',', $list_i);
    $output['titles']   = implode(',', $list_t);
    $output['sections'] = $choice;
    $output['admin']    = TRUE;
}
$TPL = new TEMPLATE(dirname(__FILE__).DS.'post.tpl');

$categories = CMS::call('POSTS')->getCategories($section);

$output['section_id']     = $section;
$output['section_title']  = $sections[$section]['title'];
$output['categories']     = $categories;
$output['category_id']    = $category;
$output['category_title'] = $categories[$category]['title'];

$output['title']    = FILTER::get('REQUEST', 'title');
$output['keywords'] = FILTER::get('REQUEST', 'keywords');
$output['desc']     = FILTER::get('REQUEST', 'desc');
$output['text']     = FILTER::get('REQUEST', 'text');
$opened = FILTER::get('REQUEST', 'opened');
$output['opened']   = empty($opened) ? TRUE : $opened;

if (FILTER::get('REQUEST', 'edit') && USER::$root) {
    $content = CMS::call('POSTS')->getContent($category);
    $post    = CMS::call('POSTS')->getItem($post, 'full', FALSE);
    $output['item']     = $post['id'];
    $output['title']    = empty($output['title'])    ? $post['title']    : $output['title'];
    $output['keywords'] = empty($output['keywords']) ? $post['keywords'] : $output['keywords'];
    $output['desc']     = empty($output['des'])      ? $post['desc']     : $output['desc'];
    $output['text']     = empty($output['text'])     ? $post['text']     : $output['text'];
    $output['opened']   = empty($output['opened'])   ? $post['opened']   : $output['opened'];
    $output['header']   = __('Edit');
} else {
    $output['item']   = '';
    $output['header'] = __('New post');
}

$output['sections'][$output['section_id']]['selected']    = TRUE;
$output['categories'][$output['category_id']]['selected'] = TRUE;
$output['bbCodes_desc'] = CMS::call('PARSER')->showBbcodesPanel('post.desc');
$output['bbCodes_text'] = CMS::call('PARSER')->showBbcodesPanel('post.text');
ShowWindow(__('Post'), $TPL->parse($output));
