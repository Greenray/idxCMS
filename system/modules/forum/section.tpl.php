<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - SECTION TEMPLATE

die();?>
<table id="std">
    <tr><th colspan="4"><a href="{link}">{title}</a></th></tr>
    <tr><th colspan="2">[__Title]</th><th>[__Description]</th><th>[__Topics]</th></tr>
    [each=categories]
        <tr>
            <td><img src="{categories[path]}icon.png" width="35" height="35" alt="" /></td>
            <td class="left"><a href="{categories[link]}">{categories[title]}</a></td>
            <td class="left">{categories[desc]}</td>
            <td class="right">{categories[topics]}</td>
        </tr>
    [endeach.categories]
</table>
<table>
    <tr>
        <td class="center" width="20%">[__Categories]: {total_categories}</td>
        <td class="center" width="20%">[__Topics]: {total_topics}</td>
        <td class="center" width="20%">[__Replies]: {total_replies}</td>
        <td class="center" width="20%">[__Views]: {total_views}</td>
    </tr>
</table>
