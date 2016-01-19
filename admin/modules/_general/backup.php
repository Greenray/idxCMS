<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Backups managment.

if (!defined('idxADMIN') || !USER::$root) die();

try {
    if (!empty($REQUEST['save'])) {
        if (!empty($REQUEST['dir']) || !empty($REQUEST['file'])) {
            $exclude_files = ['arj','avi','bzip','bzip2','gz','gzip','mp3','mov','mpeg','rar','tar','wmv','zip'];
            #
            # Backup filename
            #
            $PHAR = new PharData(BACKUPS.'backup_'.date('H-i-s_d.m.Y').'.tar.gz');
            #
            # Backup all files in selected directories
            #
            if (!empty($REQUEST['dir'])) {
                foreach($REQUEST['dir'] as $dir) {
                    $list = GetFilesList(CONTENT.$dir);
                    foreach ($list as $file) {
                        $info = pathinfo(CONTENT.$dir.DS.$file);
                        if (empty($info['extension']) || !in_array($info['extension'], $exclude_files)) {
                            $PHAR->addFile(CONTENT.$dir.DS.$file);
                        }
                    }
                }
            }
            #
            # Backup file
            #
            if (!empty($REQUEST['file'])) {
                foreach($REQUEST['file'] as $file) {
                    $PHAR->addFile(CONTENT.$file);
                }
            }

        } elseif (!empty($REQUEST['delete']) && !empty($REQUEST['backups'])) {
            #
            # Delete selected backups
            #
            foreach ($REQUEST['backups'] as $file) {
                if (file_exists(BACKUPS.$file)) {
                    unlink(BACKUPS.$file);
                }
            }
        } else ShowError('Nothing selected');
    }
} catch (Exception $error) {
    ShowError($error->getMessage());
}
#
# INTERFACE
#
$backups = GetFilesList(BACKUPS);
$output  = [];
$output['total'] = FormatSize(GetDirSize(BACKUPS));
foreach ($backups as $key => $file) {
    $output['backups'][$key]['name'] = $file;
    $output['backups'][$key]['size'] = FormatSize(filesize(BACKUPS.$file));
}

$dirs = AdvScanDir(CONTENT, '', 'dir', FALSE, ['temp', 'backups']);
foreach ($dirs as $key => $dir) {
    $output['dirs'][$key]['name'] = $dir;
    $output['dirs'][$key]['size'] = FormatSize(GetDirSize(CONTENT.$dir.DS));
}

$files = AdvScanDir(CONTENT, '', 'file', FALSE);
foreach ($files as $key => $file) {
    $output['files'][$key]['name'] = $file;
    $output['files'][$key]['size'] = FormatSize(filesize(CONTENT.$file));
}

$TPL = new TEMPLATE(__DIR__.DS.'backup.tpl');
$TPL->set($output);
echo $TPL->parse();
