<?php
# idxCMS Flat Files Content Management Sysytem
# Module Forum
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<table id="std">
    <tr>
        <th colspan="2">[__Title]</th>
        <th>[__Description]</th>
        <th>[__Topics]</th>
    </tr>
    <tr><td colspan="4"class="center"><a href="{link}">{title}</a></td></tr>
    [each=categories]
        <tr>
            <td style="width:40px"><img src="{categories[path]}icon.png" width="35" height="35" alt="" /></td>
            <td class="left"><a href="{categories[link]}">{categories[title]}</a></td>
            <td class="left">{categories[desc]}</td>
            <td class="center" style="width:40px">{categories[topics]}</td>
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
