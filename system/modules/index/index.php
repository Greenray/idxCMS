<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module INDEX

if (!defined('idxCMS')) die();

if (!USER::$logged_in && CONFIG::getValue('main', 'welcome')) {
    #
    # Show into page for guests or switch off it in config file
    #
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'intro.tpl');
    $TEMPLATE->set('intro', file_get_contents(CONTENT.'intro'));
    SYSTEM::defineWindow('You are welcome!', $TEMPLATE->parse());
}

SYSTEM::set('pagename', __('Index'));
SYSTEM::setPageDescription(__('Index'));

$sections = CMS::call('POSTS')->getSections();
#
# Don't show system section "Drafts"
#
unset($sections['drafts']);

if (!empty($sections)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'index.tpl');
    $tab    = 0;
    $output = '';

    foreach ($sections as $id => $section) {
        $categories = CMS::call('POSTS')->getCategories($id);

        foreach ($categories as $key => $category) {
            $content = CMS::call('POSTS')->getContent($key);
            #
            # Show last five posts
            #
            $list = array_slice($content, -5, 5, TRUE);
            $pos  = 1;
            ++$tab;
            $TEMPLATE->set('tab', $tab);
            $TEMPLATE->set('icon', $category['path']);

            $post = [];

            foreach ($list as $item => $data) {
                #
                # We need only post description
                #
                $post[$pos] = CMS::call('POSTS')->getItem($item, 'desc');

                $post[$pos]['section_title']  = $section['title'];
                $post[$pos]['section_link']   = $section['link'];
                $post[$pos]['category_title'] = $category['title'];
                $post[$pos]['category_link']  = $category['link'];

                $post[$pos]['tab']      = $id.'-'.$pos;
                $post[$pos]['tab_date'] = FormatTime('d m Y', $post[$pos]['time']);

                $post[$pos]['comment']  = ($post[$pos]['comments'] > 0) ? $post[$pos]['link'].COMMENT.$post[$pos]['comments'] : NULL;
                ++$pos;
            }
            $TEMPLATE->set('posts', $post);
            $output .= $TEMPLATE->parse();
        }
    }

    $_SESSION['tabs'] = $tab;
    SYSTEM::defineWindow('Index', $output);

} else SYSTEM::showMessage('Database is empty', MODULE.'index');
