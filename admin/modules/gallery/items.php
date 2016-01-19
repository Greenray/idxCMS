<?php
# idxCMS Flat Files Content Management Sysytem v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Images management.

if (!defined('idxADMIN')) die();

$section    = FILTER::get('REQUEST', 'section');
$category   = FILTER::get('REQUEST', 'category');
$item       = FILTER::get('REQUEST', 'edit');
$sections   = CMS::call('GALLERY')->getSections();
$categories = CMS::call('GALLERY')->getCategories($section);
$content    = CMS::call('GALLERY')->getContent($category);

$new_category = FILTER::get('REQUEST', 'new_category');
#
# Save new or edited item
#
if (!empty($REQUEST['save'])) {
    try {
        #
        # Check if admin decided to move item
        #
        if ($category !== $new_category) {
            if (!empty($REQUEST['item']))
                 #
                 # Item exists, so move it
                 #
                 $item = CMS::call('GALLERY')->moveItem($REQUEST['item'], $section, $new_category);
            else $item = '';     # Nothing to move, so add new

            unset($new_category);
        } else {
            $item = FILTER::get('REQUEST', 'item');
        }
        $content = CMS::call('GALLERY')->getContent($category);
        CMS::call('GALLERY')->saveImage($item);
        $item = '';
    } catch (Exception $error) {
         echo SYSTEM::showError($error->getMessage());
    }
} elseif (!empty($REQUEST['close']) || !empty($REQUEST['open'])) {
    CMS::call('GALLERY')->setValue(
        empty($REQUEST['close']) ? $REQUEST['open'] : $REQUEST['close'],
        'opened',
        empty($REQUEST['close']) ? TRUE : FALSE
    );
} else {
    if (!empty($REQUEST['delete'])) {
        try {
            CMS::call('GALLERY')->removeItem($REQUEST['delete']);
        } catch (Exception $error) {
            echo SYSTEM::showError($error->getMessage());
        }
    }
}

if (!empty($REQUEST['new']) || !empty($item)) {
    $output = [];
    $output['section_id']     = $section;
    $output['section_title']  = $sections[$section]['title'];
    $categories = CMS::call('GALLERY')->getCategories($section);
    $output['categories'] = $categories;
    if (!empty($category)) {
        $output['category_id']    = $category;
        $output['category_title'] = $categories[$category]['title'];
        $output['categories'][$output['category_id']]['selected'] = TRUE;
    }
    $item = CMS::call('GALLERY')->getItem($item, 'full', FALSE);
    if (!empty($item)) {
        $output['id']        = $item['id'];
        $output['title']     = empty($REQUEST['title'])     ? $item['title']     : $REQUEST['title'];
        $output['keywords']  = empty($REQUEST['keywords'])  ? $item['keywords']  : $REQUEST['keywords'];
        $output['desc']      = empty($REQUEST['desс'])      ? $item['desc']      : $REQUEST['desc'];
        $output['text']      = empty($REQUEST['text'])      ? $item['text']      : $REQUEST['text'];
        $output['copyright'] = empty($REQUEST['copyright']) ? $item['copyright'] : $REQUEST['copyright'];
        $output['opened']    = empty($REQUEST['opened'])    ? $item['opened']    : $REQUEST['opened'];
    } else {
        $output['id']        = '';
        $output['title']     = empty($REQUEST['title'])     ? ''   : $REQUEST['title'];
        $output['keywords']  = empty($REQUEST['keywords'])  ? ''   : $REQUEST['keywords'];
        $output['desc']      = empty($REQUEST['desc'])      ? ''   : $REQUEST['desc'];
        $output['text']      = empty($REQUEST['text'])      ? ''   : $REQUEST['text'];
        $output['copyright'] = empty($REQUEST['copyright']) ? ''   : $REQUEST['copyright'];
        $output['opened']    = empty($REQUEST['opened'])    ? TRUE : $REQUEST['opened'];
    }
    $output['bbCodes_desc'] = CMS::call('PARSER')->showBbcodesPanel('item.desc');
    $output['bbCodes_text'] = CMS::call('PARSER')->showBbcodesPanel('item.text');
    if (!empty($REQUEST['edit'])) {
        $output['name']  = $item['image'];
        $output['image'] = GALLERY.$section.DS.$category.DS.$item['id'].DS.$item['image'];
    } else {
        $output['image'] = '';
    }

    $TPL = new TEMPLATE(__DIR__.DS.'image.tpl');
    $TPL->set($output);
    echo $TPL->parse();

} else {
    $sections = CMS::call('GALLERY')->getSections();
    if (!empty($sections[$section])) {
        $categories = CMS::call('GALLERY')->getCategories($section);
        if (!empty($categories[$category])) {
            $output = [];
            $output['header']         = __('Gallery');
            $output['section_id']     = $section;
            $output['section_title']  = $sections[$section]['title'];
            $output['category_id']    = $category;
            $output['category_title'] = $categories[$category]['title'];
            $content = CMS::call('GALLERY')->getContent($category);

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
}
