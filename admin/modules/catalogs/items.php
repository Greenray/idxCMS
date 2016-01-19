<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module Gallery: Items management.

if (!defined('idxADMIN')) die();

$section      = FILTER::get('REQUEST', 'section');
$category     = FILTER::get('REQUEST', 'category');
$new_category = FILTER::get('REQUEST', 'new_category');
$item         = FILTER::get('REQUEST', 'edit');
$sections     = CMS::call('CATALOGS')->getSections();
$categories   = CMS::call('CATALOGS')->getCategories($section);
$content      = CMS::call('CATALOGS')->getContent($category);
#
# Save new or edited item
#
if (!empty($REQUEST['save'])) {
    #
    # Check if admin decided to move post
    #
    $item = $REQUEST['item'];
    if ($category !== $new_category) {
        if (!empty($item))
             #
             # Item exists, so move it
             #
             $item = CMS::call('CATALOGS')->moveItem($item, $section, $new_category);
        else $item = '';     # Nothing to move, so add new
        $category = $new_category;
    }

    $content = CMS::call('CATALOGS')->getContent($category);

    try {
        CMS::call('CATALOGS')->saveItem($item);
        $item = '';
    } catch (Exception $error) {
        echo SYSTEM::showError($error->getMessage());
    }

} elseif (!empty($REQUEST['close']) || !empty($REQUEST['open'])) {
    CMS::call('CATALOGS')->setValue(
        empty($REQUEST['close']) ? $REQUEST['open'] : $REQUEST['close'],
        'opened',
        empty($REQUEST['close']) ? 1 : 0
    );

} else {
    if (!empty($REQUEST['delete'])) {
        try {
            CMS::call('CATALOGS')->removeItem($REQUEST['delete']);
        } catch (Exception $error) {
            echo SYSTEM::showError($error->getMessage());
        }
    }
}

if (!empty($REQUEST['new']) || !empty($item)) {
    $output = [];
    $item   = CMS::call('CATALOGS')->getItem($item, 'full', FALSE);

    if (!empty($item)) {
        $output = $item;
        $output['title']     = empty($REQUEST['title'])     ? $item['title']     : $REQUEST['title'];
        $output['keywords']  = empty($REQUEST['keywords'])  ? $item['keywords']  : $REQUEST['keywords'];
        $output['desc']      = empty($REQUEST['desс'])      ? $item['desc']      : $REQUEST['desc'];
        $output['text']      = empty($REQUEST['text'])      ? $item['text']      : $REQUEST['text'];
        $output['copyright'] = empty($REQUEST['copyright']) ? $item['copyright'] : $REQUEST['copyright'];
        $output['opened']    = empty($REQUEST['opened'])    ? $item['opened']    : $REQUEST['opened'];
    } else {
        $output['id']        = '';
        $output['title']     = $REQUEST['title'];
        $output['keywords']  = $REQUEST['keywords'];
        $output['desc']      = $REQUEST['desc'];
        $output['text']      = $REQUEST['text'];
        $output['copyright'] = $REQUEST['copyright'];
        $output['opened']    = empty($REQUEST['opened']) ? TRUE : $REQUEST['opened'];
    }
    $output['categories'] = $categories;
    if (!empty($category)) {
        $output['category_id']    = $category;
        $output['category_title'] = $categories[$category]['title'];
        $output['categories'][$output['category_id']]['selected'] = TRUE;
    }
    $output['bbCodes_desc'] = CMS::call('PARSER')->showBbcodesPanel('item.desc');
    $output['bbCodes_text'] = CMS::call('PARSER')->showBbcodesPanel('item.text');

    switch ($section) {
        case 'links':
            $output['site'] = empty($item) ? $REQUEST[ 'site'] : $item['site'];
            $template = __DIR__.DS.'link.tpl';
            break;

        case 'files':
            $template = __DIR__.DS.'file.tpl';
            break;

        default:
            $template = __DIR__.DS.'item.tpl';
            break;
    }

    $TPL = new TEMPLATE($template);
    $TPL->set($output);
    echo $TPL->parse();

} elseif (!empty($sections[$section])) {
    $categories = CMS::call('CATALOGS')->getCategories($section);
    if (!empty($categories[$category])) {
        $output = [];
        $output['section_id']     = $section;
        $output['section_title']  = $sections[$section]['title'];
        $output['category_id']    = $category;
        $output['category_title'] = $categories[$category]['title'];
        $content = CMS::call('CATALOGS')->getContent($category);
        foreach ($content as $key => $item) {
            $item['date'] = FormatTime('d m Y', $item['time']);
            if ($item['opened']) {
                $item['command'] = __('Close');
                $item['action']  = 'close';
            } else {
                $item['command'] = __('Open');
                $item['action']  = 'open';
            }
            $output['items'][] = $item;
        }

        $TPL = new TEMPLATE(__DIR__.DS.'items.tpl');
        $TPL->set($output);
        echo $TPL->parse();

    } else {
        header('Location: '.MODULE.'admin&id=catalogs.categories');
        die();
    }
} else {
    header('Location: '.MODULE.'admin&id=catalogs.categories');
    die();
}
