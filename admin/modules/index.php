<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GENERAL INFORMATION

if (!defined('idxADMIN')) die();
if (!USER::loggedIn()) die();

if (!empty($REQUEST['clean'])) {
    $files = GetFilesList(TEMP);
    if (!empty($files)) {
        foreach ($files as $file) {
            unlink(TEMP.$file);
        }
    }
}

$constants = get_defined_constants();
$output = array();
$output['server'] = $_SERVER['SERVER_SOFTWARE'];
$output['os']     = php_uname('s').' '.php_uname('r').' '.php_uname('v');
$output['php']    = phpversion();
$output['host']   = php_uname('n');
$output['rights'] = USER::getUserRights();

if (CMS::call('USER')->checkRoot()) {
    $output['file_uploads'] = ini_get('file_uploads');
    $output['file_uploads'] = empty($output['file_uploads']) ? 'Off' : 'On';
    $output['upload_max_filesize'] = ini_get('upload_max_filesize');
    $output['smtp'] = ini_get('SMTP');
    $output['display_errors'] = ini_get('display_errors');
    $output['display_errors'] = empty($output['display_errors']) ? 'Off' : 'On';
    $output['admin']  = TRUE;
    $output['rights'] = __('You have all rights on this site');
    $output['posts']  = format_size(get_dir_size(POSTS));
    if (isset(SYSTEM::$modules['catalogs'])) {
        $output['catalogs']  = format_size(get_dir_size(CATALOGS));
    }
    if (isset(SYSTEM::$modules['galleries'])) {
        $output['galleries'] = format_size(get_dir_size(GALLERIES));
    }
    if (isset(SYSTEM::$modules['forum'])) {
        $output['forum']     = format_size(get_dir_size(FORUM));
    }
    if (isset(SYSTEM::$modules['aphorisms'])) {
        $output['aphorisms'] = format_size(get_dir_size(APHORISMS));
    }
    if (isset(SYSTEM::$modules['banners'])) {
        $output['banners']   = format_size(get_dir_size(BANNERS));
    }
    $output['users']     = '('.sizeof(GetFilesList(USERS)).') '.format_size(get_dir_size(USERS));
    $output['pm']        = format_size(get_dir_size(PM_DATA));
    $output['avatars']   = format_size(get_dir_size(AVATARS));
    $output['statistic'] = format_size(filesize(CONTENT.'stats'));
    $output['bots']      = format_size(filesize(CONTENT.'spiders'));
    $output['keywords']  = format_size(filesize(CONTENT.'keywords'));
    $output['logs']      = format_size(get_dir_size(LOGS));
    $output['backups']   = format_size(get_dir_size(BACKUPS));
    $FEEDBACK = new MESSAGE(CONTENT, 'feedback');
    $output['feedback']  = '<strong>'.sizeof($FEEDBACK->getMessages()).'</strong>';
    $output['temp']      = format_size(get_dir_size(TEMP));
}

# Checking, whether there are articles expecting publications
if (CMS::call('USER')->checkRoot()) {
    CMS::call('POSTS')->getSection('drafts');
    $content = CMS::call('POSTS')->getContent(2);
    $output['wait'] = '<strong>'.sizeof($content).'</strong>';
}

$tpl = '
<div class="module">[__Welcome to administration panel]</div>
<fieldset>
    <table class="std">
        <tr><th colspan="2">[__Information]</th></tr>
        <tr class="odd"><td>[__Server]</td><td>{server}</td></tr>
        <tr class="odd"><td>[__Operation system]</td><td>{os}</td></tr>
        <tr class="odd"><td>[__Host]</td><td>{host}</td></tr>
        <tr class="odd"><td>[__Version of] php</td><td>{php}</td></tr>
        <tr class="odd"><td>[__Version of] idxCMS</td><td>{IDX_VERSION}</td></tr>
        <tr class="odd"><td class="center" colspan="2">{rights}</td></tr>
        [if=admin]
            <tr class="odd"><td><a href="{MODULE}admin&id=posts.categories">[__Posts]</a></td><td>{posts}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=catalogs.categories">[__Catalogs]</a></td><td>{catalogs}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=galleries.categories">[__Galleries]</a></td><td>{galleries}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=forum.categories">[__Forum]</a></td><td>{forum}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=aphorisms.aphorisms">[__Aphorisms]</a></td><td>{aphorisms}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=banners.banners">[__Banners]</td><td>{banners}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_user.profile">[__Users profiles]</a></td><td>{users}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_user.message">[__Private messages]</a></td><td>{pm}</td></tr>
            <tr class="odd"><td>[__Avatars]</td><td>{avatars}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_statistic.statistic">[__Site statistic]</a></td><td>{statistic}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_statistic.statistic">[__Search bots]</a></td><td>{bots}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_statistic.keywords">[__Keywords]</a></td><td>{keywords}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_statistic.logs">[__Logs]</a></td><td>{logs}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_general.backup">[__Backups]</a></td><td>{backups}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=posts.categories">[__Posts awaits moderation]</a></td><td>{wait}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=_user.feedback">[__Feedback requests]</a></td><td>{feedback}</td></tr>
            <tr class="odd"><td><a href="{MODULE}admin&id=main&clean=temp">[__Temporary files]</a></td><td>{temp}</td></tr>
            <tr><th colspan="2">[__Additional information]</th></tr>
            <tr class="odd"><td>[__File uploads]</td><td>{file_uploads}</td></tr>
            <tr class="odd"><td>[__Max file size]</td><td>{upload_max_filesize}</td></tr>
            <tr class="odd"><td>SMTP</td><td>{smtp}</td></tr>
            <tr class="odd"><td>[__Display errors]</td><td>{display_errors}</td></tr>
        [endif]
    </table>
</fieldset>';

$TPL = new TEMPLATE($tpl);
echo $TPL->parse($output);
?>