<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - LIST OF TOPICS IN CATEGORY TEMPLATE

die();?>
<table id="std">
    <tr><th colspan="2">[__Title]</th><th>[__Author]</th><th>[__Date]</th><th>[__Views]</th><th>[__Replies]</th></tr>
    [each=topic]
        <tr>
            <td class="center"><img src="{ICONS}{topic[flag]}.png" width="16" height="16" alt="" /></td>
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
