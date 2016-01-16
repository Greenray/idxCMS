<?php
/**
 * Functions for administration of the system.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
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
 * Login form administrator.
 *
 * @return string Login form for administrator
 * @todo   Create the template
 */
function LoginForm() {
    return '
        <form name="login" method="post" action="">
            <table>
                <tr>
                    <td class="row2">'.__('Username').':</td>
                    <td class="row2 left"><input type="text" name="user" value="" style="width: 95%;" /></td>
                </tr>
                <tr>
                    <td class="row2">'.__('Password').':</td>
                    <td class="row2 left"><input type="password" name="password" value="" style="width: 95%;" /></td>
                </tr>
                <tr><td class="row2" colspan="2"><input type="submit" name="login" value="'.__('Log in').'" /></td></tr>
            </table>
        </form>
        ';
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

    $TPL = new TEMPLATE(__DIR__.DS.'colors.tpl');
    $TPL->set('name',      $name);
    $TPL->set('def_color', $def_color);
    $TPL->set('colors',    $output['color']);
    $TPL->set('gray',      $output['gray']);
    return $TPL->parse();
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
        SYSTEM::showError($error->getMessage());
    }
    return TRUE;
}
