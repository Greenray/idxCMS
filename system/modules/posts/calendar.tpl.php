<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<table class="calendar">
    <tr><th colspan="7">{month}</th></tr>
    <tr>{header}</tr>
    [each=calendar]
        <tr>
            [if=calendar[span]]<td colspan="{calendar[span]}">&nbsp;</td>[/if]
            [each=calendar[dates]]
                <td class="{dates[class]} center">[if=dates[events]]<a href="{dates[events]}">[/if]{dates[date]}[if=dates[events]]</a>[/if]</td>
            [/each.calendar[dates]]
        </tr>
    [/each.calendar]
</table>
<form name="calendar center" method="post" action="">
    <select name="cal-month">
        [each=months]<option value="{months[num]}" [if=months[selected]]selected="selected"[/if]>{months[name]}</option>[/each.months]
    </select>
    <select name="cal-year">
        [each=years]<option value="{years[year]}" [if=years[selected]]selected="selected"[/if]>{years[year]}</option>[/each.years]
    </select>
    <input type="submit" value="OK" class="submit" />
</form>
