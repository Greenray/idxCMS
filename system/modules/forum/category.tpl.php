<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE FORUM - LIST OF TOPICS IN CATEGORY TEMPLATE

die();?>

<table id="std">
    <tr>
        <th colspan="2">[__Title]</th>
        <th style="width:100px">[__Author]</th>
        <th style="width:80px">[__Date]</th>
        <th style="width:60px">[__Views]</th>
        <th style="width:40px">[__Replies]</th>
    </tr>
    [each=topic]
        <tr>
            <td class="center" style="width:20px"><img src="{ICONS}{topic[flag]}.png" width="16" height="16" alt="" /></td>
            <td>
                [if=topic[pinned]]<img src="{ICONS}pinned.png" width="16" height="16" alt="Pinned" />&nbsp;[endif]
                <a href="{topic[link]}">{topic[title]}</a>
            </td>
            <td>{topic[nick]}</td>
            <td class="center">{topic[date]}</td>
            <td class="right">{topic[views]}</td>
            [ifelse=topic[comments]]
                <td class="right"><a href="{topic[last_link]}">{topic[comments]}</a></td>
            [else]
                <td class="center"> - </td>
            [endelse]
        </tr>
    [endeach.topic]
</table>
<div>
[if=post_allowed]
    <p class="right">
        <form name="form" method="post" action="">
            <input type="submit" name="new" value="[__New topic]" class="submit" />
        </form>
    </p>
[endif]
</div>
