<?php
/**
 * @package   idxCMS
 * @ingroup   MODULES INDEX
 * @file      system/modules/index/index.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            Reloadcms Team http://reloadcms.com\n
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 * @see       https://github.com/Greenray/idxCMS/system/modules/index/index.php
 */

if (!defined('idxCMS')) die();

if (!USER::loggedIn() && CONFIG::getValue('main', 'welcome')) {
    if (file_exists(CONTENT.'intro')) {
        $intro = file_get_contents(CONTENT.'intro');
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'intro.tpl');
        ShowWindow(__('You are welcome!'), $TPL->parse(array('intro' => $intro)));
    }
}

$module = CONFIG::getValue('main', 'index-module');

if (!empty(SYSTEM::$modules[$module])) {
    include_once(MODULES.$module.DS.$module.'.php');    # Load main module
} else {
    SYSTEM::set('pagename', __('Index'));
    SYSTEM::setPageDescription(__('Index'));
    $sections = CMS::call('POSTS')->getSections();
    if (!empty($sections['drafts'])) {
        unset($sections['drafts']);
    }
    if (!empty($sections['archive'])) {
        unset($sections['archive']);
    }
    if (empty($sections)) {
        ShowWindow(__('Index'), __('Database is empty'), 'center');
    } else {
        # The section "news" isn't necessary to us - for "news" we have another module
        if (!empty($sections['news'])) {
            unset($sections['news']);
        }
        # Don't show system section '#drats'
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'default.tpl');
        $i = 0;
        $output = '';
        foreach ($sections as $id => $section) {
            $categories = CMS::call('POSTS')->getCategories($id);
            foreach ($categories as $key => $category) {
                $content = CMS::call('POSTS')->getContent($key);
                $list = array_slice($content, -5, 5, TRUE);
                ++$i;
                $k = 1;
                $post = array();
                $post['tab'] = $i;
                $post['section']  = $section;
                $post['category'] = $category;
                $post['posts'] = array();
                foreach ($list as $j => $data) {
                    # In article we need it's description
                    $post['posts'][$k] = CMS::call('POSTS')->getItem($j, 'desc');
                    $post['posts'][$k]['tab'] = $id.'-'.$k;
                    $post['posts'][$k]['tab_date'] = FormatTime('d m Y', $post['posts'][$k]['time']);
                    if ($post['posts'][$k]['comments'] > 0) {
                        $post['posts'][$k]['comment'] = $post['posts'][$k]['link'].COMMENT.$post['posts'][$k]['comments'];
                    }
                    ++$k;
                }
                $output .= $TPL->parse($post);
            }
        }
        unset($post);
        if (!empty($output)) {
            $_SESSION['tabs'] = $i;
            ShowWindow(__('Index'), $output);
        } else {
            ShowWindow(__('Index'), __('Database is empty'), 'center');
        }
    }
}
