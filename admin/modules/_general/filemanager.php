<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FILEMANAGER

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

function ConvertRightsString($mode) {
    $mode = str_pad($mode, 9, '-');
    $mode = strtr($mode, array('-'=>'0', 'r'=>'4', 'w'=>'2', 'x'=>'1'));
    $newmode  = '0';
    $newmode .= $mode[0] + $mode[1] + $mode[2];
    $newmode .= $mode[3] + $mode[4] + $mode[5];
    $newmode .= $mode[6] + $mode[7] + $mode[8];
    return $newmode;
}

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

$allowed = array('php','js','ini','log','gz','txt','html','css','xml');
$images  = array('gif', 'jpeg', 'jpg', 'png');

$path   = empty($REQUEST['path']) ? realpath('.').DS : $REQUEST['path'];
$path   = str_replace('\\', '/', $path);
$url    = MODULE.'admin&amp;id=_general.filemanager';
$output = array();

if (!empty($REQUEST['save'])) {
    if (!empty($REQUEST['edit'])) {
        $path_parts = pathinfo($path.$REQUEST['edit']);
        if (empty($path_parts['extension'])) {
            if (!CheckSerialized($path.$file)) {
                file_put_contents($path.$REQUEST['edit'], $REQUEST['content']);
            }
        } else {
            if (in_array($path_parts['extension'], $allowed)) {
                file_put_contents($path.$REQUEST['edit'], $REQUEST['content']);
            }
        }
        unset($REQUEST['edit']);
    } elseif (!empty($REQUEST['rights'])) {
        if (is_array($REQUEST['rights'])) {
            $rights = array();
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
            ShowMessage('Cannot upload file');
        }
    }
} elseif(!empty($REQUEST['delete'])) {
    if (!DeleteTree($path.$REQUEST['delete'])) {
        ShowMessage('Cannot delete file or directory');
    }
} elseif (!empty($REQUEST['mkdir'])) {
    if (!mkdir($path.$REQUEST['dirname'])) {
        ShowMessage('Cannot make directory');
    }
}

$output = array();
$output['back'] = ($path === realpath('.').DS) ? '' : $url.'&amp;path='.dirname($path).DS;
$output['url']  = $url;
$output['path'] = $path;
$elements = array_merge(AdvScanDir($path, '', 'dir'), AdvScanDir($path, '', 'file'));

foreach ($elements as $key => $file) {
    $output['elements'][$key]['file'] = $file;
    $filedata = stat($path.$file);
    $output['elements'][$key]['size'] = $filedata['size'];
    $output['elements'][$key]['date'] = FormatTime('d m Y', $filedata['mtime']);
    $output['elements'][$key]['time'] = FormatTime('H:i:s', $filedata['mtime']);
    if (is_dir($path.$file)) {
        $output['elements'][$key]['link']  = $url.'&amp;path='.$path.$file.DS;
        $output['elements'][$key]['empty'] = TRUE;
        $output['elements'][$key]['alert'] = 'onClick="if(confirm(\''.__('Delete this directory recursively?').'\')) document.location.href = \''.$url.'&amp;path='.$path.'&amp;delete='.$file.'\'"';
        $output['elements'][$key]['style'] = 'row2';
    } else {
        $path_parts = pathinfo($path.$file);
        if (empty($path_parts['extension'])) {
            if (!CheckSerialized($path.$file)) {
                $output['elements'][$key]['edit'] = $url.'&amp;path='.$path.'&amp;edit='.$file;
            } else {
                $output['elements'][$key]['empty'] = TRUE;
            }
        } else {
            preg_match('/[^.]+\.[^.]+$/', $path_parts['basename'], $matches);
            if ($matches[0] === 'tar.gz') {
                $output['elements'][$key]['download'] = TRUE;
            } elseif (in_array($path_parts['extension'], $allowed) && (substr($path_parts['basename'], -6) !== 'min.js')) {
                $output['elements'][$key]['edit'] = $url.'&amp;path='.$path.'&amp;edit='.$file;
            } elseif (in_array($path_parts['extension'], $images)) {
                $output['elements'][$key]['view'] = ROOT.str_replace(realpath('.').DS, '', $path).$file;
            } else {
                $output['elements'][$key]['empty'] = TRUE;
            }
        }
//        $output['elements'][$key]['alert'] = 'onClick="if(confirm(\''.__('Delete this file?').'\')) document.location.href = \''.$url.'&amp;path='.$path.'&amp;delete='.$file.'\'"';
        $output['elements'][$key]['style'] = 'row1';
    }
    $output['elements'][$key]['rights'] = GetRights($path.$file);
    $output['elements'][$key]['rights_edit'] = $url.'&amp;path='.$path.'&amp;rights='.$file;
    $output['elements'][$key]['delete'] = $url.'&amp;path='.$path.'&amp;delete='.$file;
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'filemanager.tpl');
echo $TPL->parse($output);
clearstatcache();
$output = array();

if (!empty($REQUEST['rights'])) {
    $output['file'] = $REQUEST['rights'];
    $rights = str_split(GetRights($path.$REQUEST['rights'], TRUE), 1);
    $output['owner']['r'] = ($rights[0] === 'r') ? 'r' : '';
    $output['owner']['w'] = ($rights[1] === 'w') ? 'w' : '';
    $output['owner']['x'] = ($rights[2] === 'x') ? 'x' : '';
    $output['group']['r'] = ($rights[3] === 'r') ? 'r' : '';
    $output['group']['w'] = ($rights[4] === 'w') ? 'w' : '';
    $output['group']['x'] = ($rights[5] === 'x') ? 'x' : '';
    $output['other']['r'] = ($rights[6] === 'r') ? 'r' : '';
    $output['other']['w'] = ($rights[7] === 'w') ? 'w' : '';
    $output['other']['x'] = ($rights[8] === 'x') ? 'x' : '';
    if (is_dir($path.$REQUEST['rights'])) {
        $output['dir'] = TRUE;
    }
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'rights.tpl');
    echo $TPL->parse($output);
}

if (!empty($REQUEST['edit'])) {
    $path_parts = pathinfo($path.$REQUEST['edit']);
    if (empty($path_parts['extension'])) {
        if (!CheckSerialized($path.$REQUEST['edit'], $content)) {
            $output['content'] = $content;
        }
    } else {
        if (in_array($path_parts['extension'], $allowed)) {
            if ($path_parts['extension'] === 'gz') {
                $output['content'] = gzfile_get_contents($path.$REQUEST['edit']);
            } else {
                $output['content'] = file_get_contents($path.$REQUEST['edit']);
            }
        }
    }
    $output['name'] = $REQUEST['edit'];
    if (!empty($output['content'])) {
        $TPL = new TEMPLATE(dirname(__FILE__).DS.'edit.tpl');
        echo $TPL->parse($output);
    }
}
?>