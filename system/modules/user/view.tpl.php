<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module USER: View user profile template

die();?>

<table id="std" class="right">
    <tr class="dark"><td colspan="2" class="avatar center"><img src="$avatar" hspace="5" vspace="5" alt="" /></td></tr>
    <tr class="light"><th style="width:165px;">__Username__</th><td class="left">$user</td></tr>
    <tr class="dark"><th>__Nick__</th><td class="left">$nick</td></tr>
    <tr class="light"><th>__Access level__</th><td class="left">$access</td></tr>
    <tr class="dark"><th>__Personal status__</th><td class="left">$status</td></tr>
    <tr class="light"><th>__Rate__</th><td class="left">$stars</td></tr>
    <tr class="dark">
        <th>__Rights__</th>
        <!-- IF !empty($admin) -->
            <td class="left">__You have all rights on this site__</td>
        <!-- ELSE -->
            <td class="left">$rights</td>
        <!-- ENDIF -->
    </tr>
    <tr class="dark"><th>__E-mail__</th><td class="left">$email</td></tr>
    <tr class="light"><th>__Time zone__</th><td class="left">$tz</td></tr>
    <tr class="dark"><th>__Website__</th><td class="left">$website</td></tr>
    <tr class="light"><th>__Country__</th><td class="left">$country</td></tr>
    <tr class="dark"><th>__City__</th><td class="left">$city</td></tr>
    <tr class="light"><th>__Registration__</th><td class="center">$regdate</td></tr>
    <tr class="dark"><th>__Last visit__</th><td class="center">$lastvisit</td></tr>
    <tr class="light"><th>__Visits__</th><td class="center">$visits</td></tr>
    <tr class="dark"><th>__Posts__</th><td class="center">$posts</td></tr>
    <tr class="light"><th>__Comments__</th><td class="center">$comments</td></tr>
    <tr class="dark"><th>__Topics__</th><td class="center">$topics</td></tr>
    <tr class="light"><th>__Replies__</th><td class="center">$replies</td></tr>
</table>
