<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: Template for calendar

die();?>

<table class="calendar center">
    <tr><th colspan="7">$month</th></tr>
    <tr>$header</tr>
    <!-- FOREACH calendar = $calendar -->
        <tr>
        <!-- IF !empty($calendar.span) -->
            <td colspan="$calendar.span">&nbsp;</td>
        <!-- ENDIF -->
        <!-- FOREACH date = $calendar.dates -->
            <td class="$date.class center">
            <!-- IF !empty($date.events) -->
                <a href="$date.events">
            <!-- ENDIF -->
                    $date.date
            <!-- IF !empty($date.events) -->
                </a>
            <!-- ENDIF -->
            </td>
        <!-- ENDFOREACH -->
        </tr>
    <!-- ENDFOREACH -->
    <tr>
        <td colspan="7">
            <form name="calendar" class="center" method="post" >
                <select name="cal-month">
                <!-- FOREACH month = $months -->
                    <option value="$month.num" <!-- IF !empty($month.selected) -->selected="selected"<!-- ENDIF -->>$month.name</option>
                <!-- ENDFOREACH -->
                </select>
                <select name="cal-year">
                <!-- FOREACH year = $years -->
                    <option value="$year.year" <!-- IF !empty($year.selected) -->selected="selected"<!-- ENDIF -->>$year.year</option>
                <!-- ENDFOREACH -->
                </select>
                <input type="submit" value="OK" />
            </form>
        </td>
    </tr>
</table>
