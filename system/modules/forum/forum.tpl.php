<?php
# idxCMS Flat Files Content Management Sysytem
# Module Forum
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<table id="std">
    <tr>
        <th colspan="2">[__Title]</a></th>
        <th>[__Description]</th>
        <th>[__Topics]</th>
    </tr>
    [each=sections]
        <tr><td colspan="4" class="center"><a href="{sections[link]}">{sections[title]}</a></td></tr>
        [each=sections[categories]]
            <tr>
                <td class="center" style="width:40px"><img src="{categories[path]}icon.png" width="35" height="35" alt="" /></td>
                <td class="left"><a href="{categories[link]}">{categories[title]}</a></td>
                <td class="left">{categories[desc]}</td>
                <td class="right" style="width:40px">{categories[topics]}</td>
            </tr>
        [endeach.sections[categories]]
    [endeach.sections]
</table>
<table>
    <tr>
        <th width="20%" class="center">[__Sections]</td>
        <th width="20%" class="center">[__Categories]</td>
        <th width="20%" class="center">[__Topics]</td>
        <th width="20%" class="center">[__Replies]</td>
        <th width="20%" class="center">[__Views]</td>
    </tr>
    <tr>
        <td class="center">{total_sections}</td>
        <td class="center">{total_categories}</td>
        <td class="center">{total_topics}</td>
        <td class="center">{total_replies}</td>
        <td class="center">{total_views}</td>
    </tr>
</table>
