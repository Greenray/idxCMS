<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$section  = FILTER::get('REQUEST', 'section');
$category = FILTER::get('REQUEST', 'category');
$post     = FILTER::get('REQUEST', 'item');

$sections = CMS::call('POSTS')->getSections();

if ($sections === FALSE) Redirect('posts');

if (!empty($sections['drafts'])) unset($sections['drafts']);

if (empty($sections)) {
    ShowWindow(__('Posts'), __('Database is empty'), 'center');
} elseif (!empty($post) && !empty($category) && !empty($section)) {
    # Request of post
    $categories = CMS::call('POSTS')->getCategories($section);
    # Wrong section request
    if ($categories === FALSE) Redirect('posts');

    $content = CMS::call('POSTS')->getContent($category);

    # Wrong category request
    if ($content === FALSE) Redirect('posts', $section);

    $post = CMS::call('POSTS')->getItem($post, 'text');

    # Wrong post request
    if ($post === FALSE) Redirect('posts', $section, $category);

    SYSTEM::set('pagename', $post['title'].' - '.__('Version for printer'));
    SYSTEM::setPageDescription($post['title']);
    SYSTEM::setPageKeywords($post['keywords']);
    $post['section']  = $sections[$section];
    $post['category'] = $categories[$category];
    $post['date'] = FormatTime('d F Y', $post['time']).' '.__('year');
    $post['current_time'] = FormatTime('d.m.Y', time());
    $post['copyright'] = CONFIG::getValue('main', 'copyright');
    $post['site'] = SYSTEM::get('url');
    $post['locale'] = SYSTEM::get('locale');
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'print.tpl');
    echo $TPL->parse($post);
    exit();
}
