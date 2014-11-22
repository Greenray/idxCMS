<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE COUNTER - TEMPLATE

die();?>

<table class="counter">
    <tr>
        <td class="odd">[__Total users]</td>
        <td class="odd">{registered}</td>
    </tr>
    <tr>
        <td class="odd">[__Today visitors]</td>
        <td class="odd">{todayusers}</td>
    </tr>
    <tr><td class="even" colspan="2">[__Online] - {visitors} ({regonline} [__registered])</td></tr>
    [if=regonline]
        <tr><td class="even" colspan="2">{loggedin}</td></tr>
    [endif]
    <tr>
        <td class="odd">[__Today hosts]</td>
        <td class="odd">{todayhosts}</td>
    </tr>
</table>
