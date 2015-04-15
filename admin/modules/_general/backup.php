<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Backup
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::$root) die();

if (!empty($REQUEST['backup'])) {
    if (!empty($REQUEST['dir'])) {
        $exclude_files =['arj','avi','bzip','bzip2','gz','gzip','mp3','mov','mpeg','rar','tar','wmv','zip'];

        # Backup file name
        $backup = BACKUPS.'backup_'.date('H-i-s_d.m.Y').'.tar.gz';
        $PHAR = new PharData($backup);

        foreach($REQUEST['dir'] as $dir) {
            try {
                $list = GetFilesList(CONTENT.$dir);
                foreach ($list as $file) {
                    $info = pathinfo(CONTENT.$dir.DS.$file);
                        if (!in_array($info['extension'], $exclude_files)) {
                        $PHAR->addFile(CONTENT.$dir.DS.$file);
                    }
                }
            } catch (Exception $error) {
                ShowMessage(__($error->getMessage()));
            }
        }
    } else ShowMessage(__('Nothing selected'));
}

if (!empty($REQUEST['delete']) && !empty($REQUEST['file'])) {
    foreach ($REQUEST['file'] as $file) {
        if (file_exists(BACKUPS.$file)) {
            unlink(BACKUPS.$file);
        }
    }
}

# INTERFACE
$files  = GetFilesList(BACKUPS);
$output = [];

foreach ($files as $file) {
    $output['files'][$file] = filesize(BACKUPS.$file);
}

$output['total'] = FormatSize(GetDirSize(BACKUPS));
$dirs  = AdvScanDir(CONTENT, '', 'dir', FALSE, ['temp']);

foreach ($dirs as $dir) {
    $output['dirs'][$dir] = FormatSize(GetDirSize(CONTENT.$dir.DS));
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'backup.tpl');
echo $TPL->parse($output);
