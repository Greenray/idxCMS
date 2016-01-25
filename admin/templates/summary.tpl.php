<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# System administration: Template for main page

die();?>

<div class="module">__Administration panel__</div>
<fieldset>
    <table class="std">
        <tr><th colspan="2">__Information__</th></tr>
        <tr class="light"><td>__Server__</td><td>$server</td></tr>
        <tr class="light"><td>__Operation system__</td><td>$os</td></tr>
        <tr class="light"><td>__Host__</td><td>$host</td></tr>
        <tr class="light"><td>__Version of__ php</td><td>$php</td></tr>
        <tr class="light"><td>__Version of__ idxCMS</td><td>{IDX_VERSION}</td></tr>
        <tr class="light"><td class="center" colspan="2">$rights</td></tr>
        <!-- IF !empty($admin) -->
            <tr class="light"><td><a href="{MODULE}admin&id=posts.categories">__Posts__</a></td><td>$posts</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=catalogs.categories">__Catalogs__</a></td><td>$catalogs</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=gallery.categories">__Gallery__</a></td><td>$gallery</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=forum.categories">__Forum__</a></td><td>$forum</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=aphorisms.aphorisms">__Aphorisms__</a></td><td>$aphorisms</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=banners.banners">__Banners__</td><td>$banners</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_user.profile">__Users profiles__</a></td><td>$users</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_user.message">__Private messages__</a></td><td>$pm</td></tr>
            <tr class="light"><td>__Avatars__</td><td>$avatars</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_statistics.statistics">__Site statistics__</a></td><td>$statistics</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_statistics.statistics">__Search bots__</a></td><td>$bots</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_statistics.searchwords">__Search keywords__</a></td><td>$keywords</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_statistics.logs">__Logs__</a></td><td>$logs</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_general.backup">__Backups__</a></td><td>$backups</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=posts.categories">__Posts awaits moderation__</a></td><td>$wait</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&id=_user.feedback">__Feedback requests__</a></td><td>$feedback</td></tr>
            <tr class="light"><td><a href="{MODULE}admin&clean=temp">__Temporary files__</a></td><td>$temp</td></tr>
            <tr><th colspan="2">__Additional information__</th></tr>
            <tr class="light"><td>__File uploads__</td><td>$file_uploads</td></tr>
            <tr class="light"><td>__Max file size__</td><td>$upload_max_filesize</td></tr>
            <tr class="light"><td>SMTP</td><td>$smtp</td></tr>
            <tr class="light"><td>__Display errors__</td><td>$display_errors</td></tr>
        <!-- ENDIF -->
    </table>
</fieldset>
