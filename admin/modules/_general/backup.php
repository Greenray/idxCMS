<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - BACKUP

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

require_once(ADMINLIBS.'tar.php');

if (!empty($REQUEST['backup'])) {
    $suffix = empty($REQUEST['gzip']) ? '' : '.gz';
    $TAR = new TAR();
    $TAR->isGzipped = !empty($REQUEST['gzip']);
    $TAR->filename  = BACKUPS.'backup_'.date('H-i-s_d.m.Y').'.tar'.$suffix;
    chdir(ROOT);
    foreach($REQUEST['dir'] as $dir) {
        $TAR->addDirectory(CONTENT.$dir, TRUE);
    }
    chdir(getcwd());
    if (!empty($TAR->directories)) {
        $TAR->saveTar();
        ShowMessage(__('Done').' ('.basename($TAR->filename).')');
        unset($TAR);
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
$output = array();
foreach ($files as $file) {
    $output['files'][$file] = filesize(BACKUPS.$file);
}
$output['total'] = format_size(get_dir_size(BACKUPS));
$dirs  = AdvScanDir(CONTENT, '', 'dir', FALSE, array('temp'));
foreach ($dirs as $dir) {
    $output['dirs'][$dir] = format_size(get_dir_size(CONTENT.$dir.DS));
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'backup.tpl');
echo $TPL->parse($output);
?>