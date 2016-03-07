<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: Section template

die();?>

<table id="std">
    <tr><th colspan="2">__Title__</th><th>__Description__</th><th>__Topics__</th></tr>
    <tr><td colspan="4"class="center"><a href="$link">$title</a></td></tr>
    <!-- FOREACH category = $categories -->
        <tr>
            <td style="width:40px"><img src="[$category.path:]icon.png" width="35" height="35" alt="ICON" /></td>
            <td class="left"><a href="$category.link">$category.title</a></td>
            <td class="left">$category.desc</td>
            <td class="center" style="width:40px">$category.topics</td>
        </tr>
    <!-- ENDFOREACH -->
</table>
<table>
    <tr>
        <td class="center" width="20%">__Categories__: $total_categories</td>
        <td class="center" width="20%">__Topics__: $total_topics</td>
        <td class="center" width="20%">__Replies__: $total_replies</td>
        <td class="center" width="20%">__Views__: $total_views</td>
    </tr>
</table>
