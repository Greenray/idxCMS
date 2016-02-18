<?php
/**
 * Functions for administration of the system.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      admin/libs/functions.php
 * @package   Administration
 * @overview  Administration of the system.
 *            Includes possibilities for configuring the system, managing users, modules, creating and editing content.
 */

/** Data storage for backups */
define('BACKUPS', CONTENT.'backups'.DS);

/**
 * Formats the presentation's file size.
 *
 * @param  integer $i Size value
 * @return string     Formatted size value
 */
function FormatSize($i) {
    if     (floor($i / 1073741824) > 0) return sprintf("%.2f Gb", $i / (1024 * 1024 * 1024));
    elseif (floor($i / 1048576) > 0)    return sprintf("%.2f Mb", $i / (1024 * 1024));
    elseif (floor($i / 1024) > 0)       return sprintf("%.2f Kb", $i / 1024);
    elseif ($i < 1024)                  return $i.' '.__('byte(s)');
}

/**
 * Gets size of the directory with subdirectiries.
 *
 * @param  string $dir The name of the main directory
 * @return integer     The size of scanned directory
 */
function GetDirSize($dir) {
    $size = 0;
    if ($dh = opendir($dir)) {
        while ($file = readdir($dh)) {
            if (is_dir($dir.$file)) {
                if (($file !== '.') && ($file !== '..')) {
                   $size += GetDirSize($dir.$file.DS);
                }
            } else {
                if ($file !== '.htaccess') {
                    $size += filesize($dir.$file);
                }
            }
        }
        closedir($dh);
    }
    return $size;
}

/**
 * Checs if the variable exists in array.
 * This function is recursive.
 *
 * @param  mixed $needle   Value to search
 * @param  array $haystack Array to search
 * @return boolean         The result of operation
 */
function InArrayRecursive($needle, $haystack) {
    foreach ($haystack as $value) {
        if (is_array($value))
             return InArrayRecursive($needle, $value);
        else return in_array($needle, $haystack);
    }
}

/**
 * Displays a color selection.
 *
 * @param  string $name      ID for the input field
 * @param  string $def_color Default color
 * @return string            Color selection form
 */
function GetColor($name = 'idselector', $def_color = '') {
    $col_r = 0;
    $col_g = 0;
    $col_b = 0;
    $row_return   = 0;
    $block_return = 0;
    $output = [];
    $i = 0;
    while ($col_r <= 255) {
        $col_g = 0;
        $block_return++;
        while ($col_g <= 255) {
            $col_b = 0;
            while ($col_b <= 255) {
                $red   = dechex($col_r);
                $green = dechex($col_g);
                $blue  = dechex($col_b);
                $color = str_pad($red, 2, '0', STR_PAD_LEFT).''.str_pad($green, 2, '0', STR_PAD_LEFT).''.str_pad($blue, 2, '0', STR_PAD_LEFT);
                $output['color'][$i]['color'] = $color;
                $row_return++;
                if ($row_return === 18) {
                    $row_return = 0;
                    $output['color'][$i]['tr'] = TRUE;
                }
                $col_b += 51;
                $i++;
            }
            $col_g += 51;
        }
        $col_r += 51;
    }
    $col = 15;
    while ($col <= 255) {
        $red   = strtoupper(dechex($col));
        $green = strtoupper(dechex($col));
        $blue  = strtoupper(dechex($col));
        $color = str_pad($red, 2, '0', STR_PAD_LEFT).''.str_pad($green, 2, '0', STR_PAD_LEFT).''.str_pad($blue, 2, '0', STR_PAD_LEFT);
        $output['gray'][$col]['gray'] = $color;
        $col += 15;
    }

    $TEMPLATE = new TEMPLATE(__DIR__.DS.'colors.tpl');
    $TEMPLATE->set('name',      $name);
    $TEMPLATE->set('def_color', $def_color);
    $TEMPLATE->set('colors',    $output['color']);
    $TEMPLATE->set('gray',      $output['gray']);
    return $TEMPLATE->parse();
}

/**
 * Saves sorteg sections with categories.
 *
 * @param  object $obj    Object (posts, forum, etc...)
 * @param  array  $params Sections data
 * @return boolean        The result of operation
 */
function SaveSortedSections($obj, $params) {
    $params = explode('&', $params);
    $sorted = [];
    foreach ($params as $key => $param) {
        $items = explode('.', $param);
        if (empty($items[1])) {
            # Param has no category value
            $section = $items[0];
            $sorted[$section] = [];
        } else {
            $sorted[$section][] = $param;
        }
    }
    $sections = [];
    foreach ($sorted as $section => $values) {
        $new_categories = [];
        $categories     = CMS::call($obj)->getCategories($section);
        foreach ($values as $i => $id) {
            #
            # Get section and category
            #
            $old = explode('.', $id);
            if ($old[0] === $section) {
                $new_categories[$old[1]] = $categories[$old[1]];
            } else {
                $moved = CMS::call($obj)->moveCategory($old[1], $old[0], $section);
                #
                # Category moved with new ID
                #
                $new_categories[$moved]       = CMS::call($obj)->getCategory($moved);
                $new_categories[$moved]['id'] = $moved;
            }
        }
        $sections[$section] = $new_categories;
    }
    try {
        foreach ($sections as $section => $categories) {
            CMS::call($obj)->saveCategories($section, $categories);
        }
    } catch (Exception $error) {
        ShowError($error->getMessage());
    }
    return TRUE;
}

/**
 * Shows message.
 *
 * @param  string  $message  Message
 * @param  string  $url      Url for redirection
 */
function ShowMessage($message, $url = '') {
    $TEMPLATE = new TEMPLATE(TEMPLATES.'message.tpl');
    $TEMPLATE->set('message', __($message));
    $TEMPLATE->set('url', $url);
    echo $TEMPLATE->parse();
}

/**
 * Shows error.
 *
 * @param  string  $message  Message
 * @param  string  $url      Url for redirection
 */
function ShowError($message, $url = '') {
    $TEMPLATE = new TEMPLATE(TEMPLATES.'error.tpl');
    $TEMPLATE->set('message', __($message));
    $TEMPLATE->set('url', $url);
    echo $TEMPLATE->parse();
}
