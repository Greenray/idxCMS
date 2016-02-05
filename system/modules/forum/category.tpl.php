<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
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
            <td class="icon icon-flag center" style="width:20px">&nbsp;</td>
            <td><a href="$topic.link"<!-- IF !empty($topic.pinned) --> class="icon icon-pinned"<!-- ENDIF -->>$topic.title</a></td>
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
        <form name="form" method="post" >
            <input type="submit" name="new" value="__New topic__" />
        </form>
    </div>
<!-- ENDIF -->
