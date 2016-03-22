<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Publications and news.

if (!defined('idxADMIN')) die();

$section  = FILTER::get('REQUEST', 'section');
$category = FILTER::get('REQUEST', 'category');
$item     = FILTER::get('REQUEST', 'item');
$post     = FILTER::get('REQUEST', 'edit');

$new_section  = FILTER::get('REQUEST', 'new_section');
$new_category = FILTER::get('REQUEST', 'new_category');

$sections   = CMS::call('POSTS')->getSections();
$categories = CMS::call('POSTS')->getCategories($section);
$content    = CMS::call('POSTS')->getContent($category);
#
# Save new or edited post
#
if (!empty($REQUEST['save'])) {
    #
    # Check if admin decided to move post
    #
    if (($section !== $new_section) || ($category !== $new_category)) {
        if (!empty($item))
             #
             # Post exists, so move it
             #
             $post = CMS::call('POSTS')->moveItem($item, $new_section, $new_category);
        else $post = '';     # Nothing to move, so add new

    } else $post = $item;    # It's edited post

    $categories = CMS::call('POSTS')->getCategories($new_section);
    $content    = CMS::call('POSTS')->getContent($new_category);

    try {
        CMS::call('POSTS')->saveItem($post);
        if (!empty($REQUEST['new'])) {
            USER::changeProfileField(USER::getUser('user'), 'posts', '+');
        }

        unset($REQUEST['new']);

    } catch (Exception $error) {
        ShowError($error->getMessage());
    }
    $post = '';

} elseif (!empty($REQUEST['close']) || !empty($REQUEST['open'])) {
    CMS::call('POSTS')->setValue(
        empty($REQUEST['close']) ? $REQUEST['open'] : $REQUEST['close'],
        'opened',
        empty($REQUEST['close']) ? 1 : 0
    );
} else {
    if (!empty($REQUEST['delete'])) {
        try {
            CMS::call('POSTS')->removeItem($REQUEST['delete']);
        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
}

if ((empty($section) && empty($category)) || !empty($REQUEST['new']) || !empty($post)) {
    if (empty($section)) {
        $section = 'drafts';
        if (USER::$root)
             $category = 1;
        else $category = 2;
    }

    $output = [];
    $choice = [];
    $list_i = [];
    $list_t = [];

    foreach ($sections as $id => $data) {
        $categories = CMS::call('POSTS')->getCategories($id);
        if (!empty($categories)) {
            #
            # Don't include sections without categories
            #
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

    $output['ids']            = implode(',', $list_i);
    $output['titles']         = implode(',', $list_t);
    $output['sections']       = $choice;
    $output['section_id']     = $section;
    $output['section_title']  = $sections[$section]['title'];
    $categories = CMS::call('POSTS')->getCategories($section);
    $output['categories']     = $categories;
    $output['category_id']    = $category;
    $output['category_title'] = $categories[$category]['title'];
    if (!empty($post)) {
        $post = CMS::call('POSTS')->getItem($post, 'full', FALSE);
        $output['item']     = $post['id'];
        $output['title']    = empty($REQUEST['title'])    ? $post['title']    : $REQUEST['title'];
        $output['keywords'] = empty($REQUEST['keywords']) ? $post['keywords'] : $REQUEST['keywords'];
        $output['desc']     = empty($REQUEST['desc'])     ? $post['desc']     : $REQUEST['desc'];
        $output['text']     = empty($REQUEST['text'])     ? $post['text']     : $REQUEST['text'];
        $output['opened']   = empty($REQUEST['opened'])   ? $post['opened']   : $REQUEST['opened'];
    } else {
        $output['item']     = '';
        $output['title']    = empty($REQUEST['title'])    ? ''   : $REQUEST['title'];
        $output['keywords'] = empty($REQUEST['keywords']) ? ''   : $REQUEST['keywords'];
        $output['desc']     = empty($REQUEST['desc'])     ? ''   : $REQUEST['desc'];
        $output['text']     = empty($REQUEST['text'])     ? ''   : $REQUEST['text'];
        $output['opened']   = empty($REQUEST['opened'])   ? TRUE : $REQUEST['opened'];
    }
    $output['sections'][$section]['selected']    = TRUE;
    $output['categories'][$category]['selected'] = TRUE;
    $output['bbCodes_desc'] = CMS::call('PARSER')->showBbcodesPanel('post.desc');
    $output['bbCodes_text'] = CMS::call('PARSER')->showBbcodesPanel('post.text');

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'post.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();

} elseif (!empty($sections[$section])) {

    $categories = CMS::call('POSTS')->getCategories($section);

    if (!empty($categories[$category])) {
        $output = [];
        $output['header']         = __('Posts');
        $output['section_id']     = $section;
        $output['section_title']  = $sections[$section]['title'];
        $output['category_id']    = $category;
        $output['category_title'] = $categories[$category]['title'];
        $content = CMS::call('POSTS')->getContent($category);

        foreach ($content as $key => $post) {
            $post['date'] = FormatTime('d m Y', $post['time']);
            if ($post['opened']) {
                $post['command'] = __('Close');
                $post['action']  = 'close';
            } else {
                $post['command'] = __('Open');
                $post['action']  = 'open';
            }
            $output['items'][] = $post;
        }

        $TEMPLATE = new TEMPLATE(__DIR__.DS.'items.tpl');
        $TEMPLATE->set($output);
        echo $TEMPLATE->parse();

    } else {
        header('Location: '.MODULE.'admin&id=posts.categories');
        die();
    }
} else {
    header('Location: '.MODULE.'admin&id=posts.categories');
    die();
}
