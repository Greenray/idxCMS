<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

/** Backups data store */
define('BACKUPS', CONTENT.'backups'.DS);

function format_size($i) {
    if     (floor($i / 1073741824) > 0) return sprintf("%.2f Gb", $i / (1024 * 1024 * 1024));
    elseif (floor($i / 1048576) > 0)    return sprintf("%.2f Mb", $i / (1024 * 1024));
    elseif (floor($i / 1024) > 0)       return sprintf("%.2f Kb", $i / 1024);
    elseif ($i < 1024)                  return $i.' '.__('byte(s)');
}


/** Get size of the directory with subdirectiries.
 * @param  string $dir The name of the main directory
 * @return integer - The size of scanned directory
 */
function get_dir_size($dir) {
    $size = 0;
    if (($dh = opendir($dir)) !== FALSE) {
        while (($file = readdir($dh)) !== FALSE) {
            if (is_dir($dir.$file)) {
                if (($file !== '.') AND ($file !== '..')) {
                   $size += get_dir_size($dir.$file.DS);
                }
            } else $size += filesize($dir.$file);
        }
        closedir($dh);
    }
    return $size;
}

function in_array_recursive($needle, $haystack) {
    foreach ($haystack as $value) {
        if (is_array($value))
             return in_array_recursive($needle, $value);
        else return in_array($needle, $haystack);
    }
}

/** Show specified translated message with additional information.
 *  @param  string $message Specified message.
 *  @param  string $info    Additional information.
 *  @return string          Formatted html table.
 */
function ShowMessage($message, $info = '') {
    echo '<table class="message center">
            <tr><td class="admin_mess">'.__($message).' '.$info.'</td></tr>
          </table>';
}

function LoginForm() {
    return '<form name="login" method="post" action="">
                <table>
                    <tr>
                        <td class="row2">'.__('Username').':</td>
                        <td class="row2 left"><input type="text" name="username" style="width: 95%;" /></td>
                    </tr>
                    <tr>
                        <td class="row2">'.__('Password').':</td>
                        <td class="row2 left"><input type="password" name="password" style="width: 95%;" /></td>
                    </tr>
                    <tr><td class="row2" colspan="2"><input type="submit" name="login" value="'.__('Log in').'" class="submit" /></td></tr>
                </table>
            </form>';
}

function GetColor($name = 'idselector', $def_color = '') {
    $output = '
    <div id="colorselector" class="none" style="background:white;border:2px solid black;position:absolute;width:300px;z-index:10">
        <table class="colortable">
            <tr><td colspan="18"><p align="center">'.__('Choose color').'</p></td></tr>
            <tr>';
    $col_r = 0;
    $col_g = 0;
    $col_b = 0;
    $row_return   = 0;
    $block_return = 0;
    while ($col_r <= 255) {
        $col_g = 0;
        $block_return++;
        while ($col_g <= 255) {
            $col_b = 0;
            while ($col_b <= 255) {
                $red     = dechex($col_r);
                $green   = dechex($col_g);
                $blue    = dechex($col_b);
                $color   = str_pad($red, 2, '0', STR_PAD_LEFT).''.str_pad($green, 2, '0', STR_PAD_LEFT).''.str_pad($blue, 2, '0', STR_PAD_LEFT);
                $output .= '<td height="12px" width="12px" bgcolor="#'.$color.'" onclick="selectColor(\'#'.$color.'\');" style="cursor: pointer;"></td>';
                $row_return++;
                if ($row_return === 18) {
                    $output    .= '</tr><tr>';
                    $row_return = 0;
                }
                $col_b += 51;
            }
            $col_g += 51;
        }
        $col_r += 51;
    }
    $output .= '<tr><td colspan="18"><p align="center">'.__('Gradation of grey color').'</p></td></tr>
                <tr>';
    $col = 15;
    while ($col <= 255) {
        $red     = strtoupper(dechex($col));
        $green   = strtoupper(dechex($col));
        $blue    = strtoupper(dechex($col));
        $color   = str_pad($red, 2, '0', STR_PAD_LEFT).''.str_pad($green, 2, '0', STR_PAD_LEFT).''.str_pad($blue, 2, '0', STR_PAD_LEFT);
        $output .= '<td height="12px" width="12px" bgcolor="#'.$color.'" onclick="selectColor(\'#'.$color.'\')" style="cursor: pointer;"></td>';
        $col    += 15;
    }
    $output .= '</tr>
               </table>
              </div>';
    return '<input class="texte" type="text" id="'.$name.'" name="'.$name.'" size="8" maxlength="8" value="'.$def_color.'" />
            <input id="'.$name.'btn" name="'.$name.'btn" type="button" value="" onclick="openColorSelector(\''.$name.'\', event)" style="padding:0 5px;background:'.$def_color.'" />'.
            $output;
}

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
            # Get section and category
            $old = explode('.', $id);
            if ($old[0] === $section) {
                $new_categories[$old[1]] = $categories[$old[1]];
            } else {
                $moved = CMS::call($obj)->moveCategory($old[1], $old[0], $section);
                # Category moved with new ID
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
        ShowMessage(__($error->getMessage()));
    }
    return TRUE;
}
