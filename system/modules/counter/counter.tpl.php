<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module COUNTER: Template

die();?>

<table class="counter">
    <tr>
        <td>__Total users__</td>
        <td class="right">$registered</td>
    </tr>
    <tr>
        <td>__Today visitors__</td>
        <td class="right">$todayusers</td>
    </tr>
    <tr>
        <td>__Today hosts__</td>
        <td class="right">$todayhosts</td>
    </tr>
    <tr><td colspan="2">__Online__ - $visitors ($regonline __registered__)</td></tr>
    <!-- IF !empty($regonline) -->
        <tr><td colspan="2">$logged_in</td></tr>
    <!-- ENDIF -->
</table>
