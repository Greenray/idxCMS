<?php
# idxCMS Flat Files Content Management Sysytem
# Module Counter
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
    [if=regonline]<tr><td colspan="2">{logged_in}</td></tr>[/if]
    <tr>
        <td>[__Today hosts]</td>
        <td>{todayhosts}</td>
    </tr>
</table>
