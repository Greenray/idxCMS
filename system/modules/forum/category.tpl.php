<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module FORUM: Category template

die();?>

<table id="std">
    <tr>
        <th colspan="2">__Title__</th>
        <th style="width:150px">__Author__</th>
        <th style="width:100px">__Date__</th>
        <th style="width:80px">__Views__</th>
        <th style="width:80px">__Replies__</th>
    </tr>
    <!-- FOREACH topic = $topics -->
        <tr>
            <td class="center" style="width:20px"><img src="{ICONS}$topic.flag.png" width="16" height="16" alt="" /></td>
            <td>
                <!-- IF !empty($topic.pinned) -->
                    <img src="{ICONS}pinned.png" width="16" height="16" alt="Pinned" />&nbsp;
                <!-- ENDIF -->
                <a href="$topic.link">$topic.title</a>
            </td>
            <td class="center">$topic.nick</td>
            <td class="center">$topic.date</td>
            <td class="center">$topic.views</td>
            <!-- IF !empty($topic.comments) -->
                <td class="center"><a href="$topic.link{COMMENT}$topic.comments">$topic.comments</a></td>
            <!-- ELSE -->
                <td class="center"> - </td>
            <!-- ENDIF -->
        </tr>
    <!-- ENDFOREACH -->
</table>
<!-- IF !empty($post_allowed) -->
    <div class="right">
        <form name="form" method="post" action="">
            <input type="submit" name="new" value="__New topic__" />
        </form>
    </div>
<!-- ENDIF -->
