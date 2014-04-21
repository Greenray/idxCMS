<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - LOGS TEMPLATE

die();?>
<div class="module">[__Logs]</div>
<fieldset>
    <form name="day" method="post" action="">
        <table class="std">
            <tr><th colspan="2">[__Daily logs]</th></tr>
            [each=day]
                <tr class="odd">
                    <td>{day[date]}</td>
                    <td><input type="checkbox" name="viewlog[]" value="{day[log]}"> [__Select]</td>
                </tr>
            [endeach.day]
        </table>
        <p align="center">
[ifelse=archive]
                <input type="hidden" name="archive" value="{archive}" />
[else]
                <input type="submit" name="build" value="[__Build monthly log archives (except current month)]" class="submit" />
                <input type="submit" name="day" value="[__Show selected]" class="submit" />
            </p>
        </form>
    </fieldset>
    <fieldset>
        <form name="month" method="post" action="">
            <table class="std">
                <tr><th colspan="2">[__Monthly logs]</th></tr>
                [each=month]
                    <tr class="odd">
                        <td>{month[date]}</td>
                        <td><input type="radio" name="browse" value="{month[log]}"> [__Select]</td>
                    </tr>
                [endeach.month]
            </table>
            <p align="center">
[endelse]
                <input type="submit" name="month" value="[__Show selected]" class="submit" />
        </p>
    </form>
</fieldset>
