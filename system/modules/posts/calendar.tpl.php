<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE POSTS - CALENDAR TEMPLATE

die();?>
<table class="calendar">
    <tr><th colspan="7">{month}</th></tr>
    <tr>{header}</tr>
    [each=calendar]
        <tr>
            [if=calendar[span]]
                <td colspan="{calendar[span]}">&nbsp;</td>
            [endif]
            [each=calendar[dates]]
                <td class="{dates[class]} center">[if=dates[events]]<a href="{dates[events]}">[endif]{dates[date]}[if=dates[events]]</a>[endif]</td>
            [endeach.calendar[dates]]
        </tr>
    [endeach.calendar]
</table>
<form name="calendar" method="post" action="" style="text-align:center">
    <select name="cal-month">
        [each=months]
            <option value="{months[num]}" [if=months[selected]]selected="selected"[endif]>{months[name]}</option>
        [endeach.months]
    </select>
    <select name="cal-year">
        [each=years]
            <option value="{years[year]}" [if=years[selected]]selected="selected"[endif]>{years[year]}</option>
        [endeach.years]
    </select>
    <input type="submit" value="OK" />
</form>
