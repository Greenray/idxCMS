<?php
/**
 * Filemanager.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      admin/modules/_general/filemeneger.php
 * @package   Administration
 */

if (!defined('idxADMIN') || !USER::$root) die();

/**
 * Translates the string value of the access rights of a file into a numeric value.
 *
 * @param  string $mode String value of the access rights of a file
 * @return string       Numeric value of the access rights of a file
 */
function ConvertRightsString($mode) {
    $mode = str_pad($mode, 9, '-');
    $mode = strtr($mode, ['-'=>'0', 'r'=>'4', 'w'=>'2', 'x'=>'1']);
    $newmode  = '0';
    $newmode .= $mode[0] + $mode[1] + $mode[2];
    $newmode .= $mode[3] + $mode[4] + $mode[5];
    $newmode .= $mode[6] + $mode[7] + $mode[8];
    return $newmode;
}

/**
 * Gets value of the access rights of a file.
 *
 * @param  string  $file File to check rights
 * @param  boolean $if   Check special values
 * @return string        File info
 */
function GetRights($file, $if = FALSE) {
    $perms = fileperms($file);
    $info = '';
    if (!$if) {
        if     (($perms & 0xC000) == 0xC000) $info = 's'; # Socket
        elseif (($perms & 0xA000) == 0xA000) $info = 'l'; # Symbolic Link
        elseif (($perms & 0x8000) == 0x8000) $info = '-'; # Regular
        elseif (($perms & 0x6000) == 0x6000) $info = 'b'; # Block special
        elseif (($perms & 0x4000) == 0x4000) $info = 'd'; # Directory
        elseif (($perms & 0x2000) == 0x2000) $info = 'c'; # Character special
        elseif (($perms & 0x1000) == 0x1000) $info = 'p'; # FIFO pipe
        else                                 $info = 'u'; # Unknown
    }
    # Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
    # Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
    # Other
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
    return $info;
}

/**
 * Sets access rights of a file.
 *
 * @param  string  $file  File to set rights
 * @param  value   $value Rights value
 * @return boolean        The result of operation
 */
function SetRights($file, $value, $recursive = FALSE) {
    $result = chmod($file, $value);
    if (is_dir($file) && $recursive) {
        $elements = AdvScanDir($file);
        foreach ($elements as $element) {
            $result = $result && SetRights($file.DS.$element, $value, TRUE);
        }
    }
    return $result;
}

/**
 * Gets unserialized data.
 * This function can automatically restore broken data.
 *
 * @param  string $file Filename
 * @return array        Unserialized data
 */
function GetUnserialized($file) {
    $data = [];
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if ($content) {
            $data = @unserialize($content);
            if (!$data) {
                $data = UnifyBr($content);
                $data = preg_replace("!s:(\d+):\"(.*?)\";!se", "'s:'.strlen('$2').':\"$2\";'", $data);
                $data = @unserialize($data);
                if (!$data) {
                    $data = [];
                } else {
                    file_put_contents($file, serialize($data), LOCK_EX);
                }
            }
        }
    }
    return $data;
}

/**
 * Checks if file is serialized.
 *
 * @param  string  $file    Filename
 * @param  string  $content Content of file
 * @return boolean|string   Decoded content of file or FALSE
 */
function CheckSerialized($file, &$content = '') {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $result = preg_match("/^(i|s|a|o|d):(.*);/si", $content);
        if ($result) {
            return GetUnserialized($content);
        }
    }
    return FALSE;
}

$allowed = ['php','js','ini','log','gz','txt','html','css','xml', 'md'];
$images  = ['gif', 'jpeg', 'jpg', 'png'];

$path   = empty($REQUEST['path']) ? realpath('.').DS : $REQUEST['path'];
$path   = str_replace('\\', '/', $path);
$url    = MODULE.'admin&id=_general.filemanager';
$output = [];

if (!empty($REQUEST['save'])) {
    if (!empty($REQUEST['edit'])) {
        $path_parts = pathinfo($path.$REQUEST['edit']);
        if (empty($path_parts['extension']))
             if (!CheckSerialized($path.$file))                file_put_contents($path.$REQUEST['edit'], $REQUEST['content']);
        else if (in_array($path_parts['extension'], $allowed)) file_put_contents($path.$REQUEST['edit'], $REQUEST['content']);
        unset($REQUEST['edit']);

    } elseif (!empty($REQUEST['rights'])) {
        if (is_array($REQUEST['rights'])) {
            $rights = [];
            for ($i = 0; $i < 9; ++$i) {
                $rights[$i] = empty($REQUEST['rights'][$i]) ? '-' : $REQUEST['rights'][$i];
            }
            SetRights(
                $path.$REQUEST['file'],
                octdec(ConvertRightsString(implode('', $rights))),
                empty($REQUEST['recursively']) ? FALSE : TRUE
            );
            clearstatcache();
            unset($REQUEST['rights']);
        }
    }
} elseif (!empty($REQUEST['upload'])) {
    if (!empty($REQUEST['upload']['name'])) {
        $REQUEST['upload']['name'] = str_replace('%', '', $REQUEST['upload']['name']);
        if (!move_uploaded_file($REQUEST['upload']['tmp_name'], $path.$REQUEST['upload']['name'])) {
            ShowError('Cannot upload file');
        }
    }
} elseif (!empty($REQUEST['delete'])) {
    if (!DeleteTree($path.$REQUEST['delete'])) ShowError('Cannot delete file or directory');
} elseif (!empty($REQUEST['mkdir'])) {
    if (!mkdir($path.$REQUEST['dirname']))     ShowError('Cannot make directory');
}

$TPL = new TEMPLATE(__DIR__.DS.'filemanager.tpl');
$TPL->set('back', ($path === realpath('.').DS) ? '' : $url.'&path='.dirname($path).DS);
$TPL->set('url',  $url);
$TPL->set('path', $path);
$elements = array_merge(AdvScanDir($path, '', 'dir'), AdvScanDir($path, '', 'file'));
$output = [];
foreach ($elements as $key => $file) {
    $output[$key]['file'] = $file;
    $filedata = stat($path.$file);
    $output[$key]['size'] = $filedata['size'];
    $output[$key]['date'] = FormatTime('d m Y', $filedata['mtime']);
    $output[$key]['time'] = FormatTime('H:i:s', $filedata['mtime']);
    if (is_dir($path.$file)) {
        $output[$key]['link']  = $url.'&path='.$path.$file.DS;
        $output[$key]['empty'] = TRUE;
        $output[$key]['alert'] = 'onClick="if (confirm(\''.__('Delete this directory recursively?').'\')) document.location.href = \''.$url.'&path='.$path.'&delete='.$file.'\'"';
        $output[$key]['style'] = 'row2';
    } else {
        $path_parts = pathinfo($path.$file);
        if (empty($path_parts['extension'])) {
            if (!CheckSerialized($path.$file))
                 $output[$key]['edit'] = $url.'&path='.$path.'&edit='.$file;
            else $output[$key]['empty'] = TRUE;
        } else {
            preg_match('/[^.]+\.[^.]+$/', $path_parts['basename'], $matches);
            if ($matches[0] === 'tar.gz')                        $output[$key]['download'] = TRUE;
            elseif (in_array($path_parts['extension'], $allowed) && (substr($path_parts['basename'], -6) !== 'min.js'))
                                                                 $output[$key]['edit'] = $url.'&path='.$path.'&edit='.$file;
            elseif (in_array($path_parts['extension'], $images)) $output[$key]['view'] = ROOT.str_replace(realpath('.').DS, '', $path).$file;
            else                                                 $output[$key]['empty'] = TRUE;
        }
        $output[$key]['style'] = 'row1';
    }
    $output[$key]['rights']      = GetRights($path.$file);
    $output[$key]['rights_edit'] = $url.'&path='.$path.'&rights='.$file;
    $output[$key]['delete']      = $url.'&path='.$path.'&delete='.$file;
}

$TPL->set('elements', $output);
echo $TPL->parse();

clearstatcache();

$output = [];

if (!empty($REQUEST['rights'])) {
    $output['file'] = $REQUEST['rights'];
    $rights = str_split(GetRights($path.$REQUEST['rights'], TRUE), 1);
    $output['owner_r'] = ($rights[0] === 'r') ? 'r' : '';
    $output['owner_w'] = ($rights[1] === 'w') ? 'w' : '';
    $output['owner_x'] = ($rights[2] === 'x') ? 'x' : '';
    $output['group_r'] = ($rights[3] === 'r') ? 'r' : '';
    $output['group_w'] = ($rights[4] === 'w') ? 'w' : '';
    $output['group_x'] = ($rights[5] === 'x') ? 'x' : '';
    $output['other_r'] = ($rights[6] === 'r') ? 'r' : '';
    $output['other_w'] = ($rights[7] === 'w') ? 'w' : '';
    $output['other_x'] = ($rights[8] === 'x') ? 'x' : '';
    if (is_dir($path.$REQUEST['rights'])) {
        $output['dir'] = TRUE;
    }

    $TPL = new TEMPLATE(__DIR__.DS.'rights.tpl');
    $TPL->set($output);
    echo $TPL->parse();
}

if (!empty($REQUEST['edit'])) {
    $path_parts = pathinfo($path.$REQUEST['edit']);
    if (empty($path_parts['extension'])) {
        if (!CheckSerialized($path.$REQUEST['edit'], $content)) {
            $output['content'] = $content;
        }
    } else {
        if (in_array($path_parts['extension'], $allowed)) {
            if ($path_parts['extension'] === 'gz')
                 $output['content'] = gzfile_get_contents($path.$REQUEST['edit']);
            else $output['content'] = file_get_contents($path.$REQUEST['edit']);
        }
    }
    $output['name'] = $REQUEST['edit'];
    if (!empty($output['content'])) {
        $TPL = new TEMPLATE(__DIR__.DS.'edit.tpl');
        $TPL->set($output);
        echo $TPL->parse();
    }
}
