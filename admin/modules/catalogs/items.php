<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Catalogs
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN')) die();

$section    = FILTER::get('REQUEST', 'section');
$category   = FILTER::get('REQUEST', 'category');
$item       = FILTER::get('REQUEST', 'edit');

$new_category = FILTER::get('REQUEST', 'new_category');

$sections   = CMS::call('CATALOGS')->getSections();
$categories = CMS::call('CATALOGS')->getCategories($section);
$content    = CMS::call('CATALOGS')->getContent($category);

# Save new or edited post
if (!empty($REQUEST['save'])) {
    # Check if admin decided to move post
    if ($category !== $new_category) {
        if (!empty($REQUEST['item']))
             # Topic exists, so move it
             $item = CMS::call('CATALOGS')->moveItem($REQUEST['item'], $section, $new_category);
        else $item = '';     # Nothing to move, so add new
        $category = $new_category;
    } else {
        $item = FILTER::get('REQUEST', 'item');
    }
    $content = CMS::call('CATALOGS')->getContent($category);
    try {
        CMS::call('CATALOGS')->saveItem($item);
        $item = '';
    } catch (Exception $error) {
         ShowMessage(__($error->getMessage()));
    }
} elseif (!empty($REQUEST['close']) || !empty($REQUEST['open'])) {
    CMS::call('CATALOGS')->setValue(
        empty($REQUEST['close']) ? $REQUEST['open'] : $REQUEST['close'],
        'opened',
        empty($REQUEST['close']) ? TRUE : FALSE
    );
} else {
    if (!empty($REQUEST['delete'])) {
        try {
            CMS::call('CATALOGS')->removeItem($REQUEST['delete']);
        } catch (Exception $error) {
            ShowMessage(__($error->getMessage()));
        }
    }
}

if (!empty($REQUEST['new']) || !empty($item)) {
    $output = [];
    $item = CMS::call('CATALOGS')->getItem($item, 'full', FALSE);
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
        $output['title']     = FILTER::get('REQUEST', 'title');
        $output['keywords']  = FILTER::get('REQUEST', 'keywords');
        $output['desc']      = FILTER::get('REQUEST', 'desc');
        $output['text']      = FILTER::get('REQUEST', 'text');
        $output['copyright'] = FILTER::get('REQUEST', 'copyright');
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
            $output['site'] = empty($item) ? FILTER::get('REQUEST', 'site') : $item['site'];
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'link.tpl');
            break;

        default:
            $TPL = new TEMPLATE(dirname(__FILE__).DS.'item.tpl');
            break;
    }
    echo $TPL->parse($output);

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
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'items.tpl');
        echo $TPL->parse($output);
    } else {
        header('Location: '.MODULE.'admin&id=catalogs.categories');
        die();
    }
} else {
    header('Location: '.MODULE.'admin&id=catalogs.categories');
    die();
}
