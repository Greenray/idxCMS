<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: Main template

die();?>

<table id="std">
    <tr><th colspan="2">__Title__</th><th>__Description__</th><th>__Topics__</th></tr>
    <!-- FOREACH section = $sections -->
        <tr><td colspan="4" class="light center"><a href="$section.link">$section.title</a></td></tr>
        <!-- FOREACH category = $section.categories -->
            <tr class="light">
                <td class="center" style="width:40px"><img src="[$category.path:]icon.png" width="35" height="35" alt="ICON" /></td>
                <td class="left"><a href="$category.link">$category.title</a></td>
                <td class="left">$category.desc</td>
                <td class="right" style="width:40px">$category.topics</td>
            </tr>
        <!-- ENDFOREACH -->
    <!-- ENDFOREACH -->
</table>
<table>
    <tr>
        <th width="20%" class="center">__Sections__</th>
        <th width="20%" class="center">__Categories__</th>
        <th width="20%" class="center">__Topics__</th>
        <th width="20%" class="center">__Replies__</th>
        <th width="20%" class="center">__Views__</th>
    </tr>
    <tr>
        <td class="center">$total_sections</td>
        <td class="center">$total_categories</td>
        <td class="center">$total_topics</td>
        <td class="center">$total_replies</td>
        <td class="center">$total_views</td>
    </tr>
</table>
