<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: Print post

if (!defined('idxCMS')) die();

$section  = FILTER::get('REQUEST', 'section');
$category = FILTER::get('REQUEST', 'category');
$post     = FILTER::get('REQUEST', 'item');
$sections = CMS::call('POSTS')->getSections();

unset($sections['drafts']);

if (empty($sections)) {
    SYSTEM::showMessage('Database is empty', MODULE.'index');

} elseif (!empty($post) && !empty($category) && !empty($section)) {

    $categories = CMS::call('POSTS')->getCategories($section);
    #
    # Wrong section request
    #
    if ($categories) {
//        Redirect('posts');

        $content = CMS::call('POSTS')->getContent($category);
        #
        # Wrong category request
        #
        if ($content) {
    //        Redirect('posts', $section);

            $post = CMS::call('POSTS')->getItem($post, 'text');
            #
            # Wrong post request
            #
            if ($post) {
        //        Redirect('posts', $section, $category);

                SYSTEM::set('pagename', $post['title'].' - '.__('Version for printer'));
                SYSTEM::setPageDescription($post['title']);
                SYSTEM::setPageKeywords($post['keywords']);

                $post['section']      = $sections[$section];
                $post['category']     = $categories[$category];
                $post['date']         = FormatTime('d F Y', $post['time']).' '.__('year');
                $post['current_time'] = FormatTime('d.m.Y', time());
                $post['copyright']    = CONFIG::getValue('main', 'copyright');
                $post['site']         = SYSTEM::get('url');
                $post['locale']       = SYSTEM::get('locale');

                $TEMPLATE = new TEMPLATE(__DIR__.DS.'print.tpl');
                $TEMPLATE->set($post);
                echo $TEMPLATE->parse();
            }
        }
    }
    exit();
}
