<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: System administration.

if (!defined('idxADMIN')) die();

if (!empty($REQUEST['clean'])) {
    $files = GetFilesList(TEMP);
    if (!empty($files)) {
        foreach ($files as $file) {
            unlink(TEMP.$file);
        }
    }
}

$output = [];
$output['server'] = $_SERVER['SERVER_SOFTWARE'];
$output['os']     = php_uname('s').' '.php_uname('r').' '.php_uname('v');
$output['php']    = phpversion();
$output['host']   = php_uname('n');
$output['rights'] = USER::getUserRights();

if (USER::$root) {
    $output['file_uploads']        = ini_get('file_uploads');
    $output['file_uploads']        = empty($output['file_uploads']) ? 'Off' : 'On';
    $output['upload_max_filesize'] = ini_get('upload_max_filesize');

    $output['display_errors']      = ini_get('display_errors');
    $output['display_errors']      = empty($output['display_errors']) ? 'Off' : 'On';

    $output['admin']  = TRUE;
    $output['rights'] = __('You have all rights on this site');
    $output['posts']  = FormatSize(GetDirSize(POSTS));
    $output['smtp']   = ini_get('SMTP');

    if (isset(SYSTEM::$modules['catalogs']))  $output['catalogs']  = FormatSize(GetDirSize(CATALOGS));
    if (isset(SYSTEM::$modules['gallery']))   $output['gallery']   = FormatSize(GetDirSize(GALLERY));
    if (isset(SYSTEM::$modules['forum']))     $output['forum']     = FormatSize(GetDirSize(FORUM));
    if (isset(SYSTEM::$modules['aphorisms'])) $output['aphorisms'] = FormatSize(GetDirSize(APHORISMS));
    if (isset(SYSTEM::$modules['banners']))   $output['banners']   = FormatSize(GetDirSize(BANNERS));

    $output['users']      = '('.sizeof(GetFilesList(USERS)).') '.FormatSize(GetDirSize(USERS));
    $output['pm']         = FormatSize(GetDirSize(PM_DATA));
    $output['avatars']    = FormatSize(GetDirSize(AVATARS));
    $output['statistics'] = FormatSize(filesize(CONTENT.'stats'));
    $output['bots']       = FormatSize(filesize(CONTENT.'spiders'));
    $output['keywords']   = FormatSize(filesize(CONTENT.'searchwords'));
    $output['logs']       = FormatSize(GetDirSize(LOGS));
    $output['backups']    = FormatSize(GetDirSize(BACKUPS));
    $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
    $output['feedback']   = '<strong>'.sizeof($FEEDBACK->getMessages()).'</strong>';
    $output['temp']       = FormatSize(GetDirSize(TEMP));
    #
    # Checking, whether there are articles expecting publications
    #
    CMS::call('POSTS')->getSection('drafts');
    $content = CMS::call('POSTS')->getContent(2);
    $output['wait'] = '<strong>'.sizeof($content).'</strong>';
}

$TEMPLATE = new TEMPLATE(TEMPLATES.'summary.tpl');
$TEMPLATE->set($output);
echo $TEMPLATE->parse();
