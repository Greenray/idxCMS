<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE COUNTER - TEMPLATE

die();?>

<table class="counter">
    <tr>
        <td>[__Total users]</td>
        <td>{registered}</td>
    </tr>
    <tr>
        <td>[__Today visitors]</td>
        <td>{todayusers}</td>
    </tr>
    <tr><td colspan="2">[__Online] - {visitors} ({regonline} [__registered])</td></tr>
    [if=regonline]
        <tr><td colspan="2">{loggedin}</td></tr>
    [endif]
    <tr>
        <td>[__Today hosts]</td>
        <td>{todayhosts}</td>
    </tr>
</table>
