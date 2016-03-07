<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Logs template.

die();?>

<div class="module">__Logs__</div>
<fieldset>
    <form name="day" method="post" >
        <table class="std">
            <tr><th colspan="2">__Daily logs__</th></tr>
            <!-- FOREACH day = $days -->
                <tr class="light">
                    <td>$day.date</td>
                    <td><input type="checkbox" name="viewlog[]" value="$day.log"> __Select__</td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p align="center">
<!-- IF !empty($archive) -->
                <input type="hidden" name="archive" value="$archive" />
<!-- ELSE -->
                <input type="submit" name="build" value="__Build monthly log archives (except current month)__" />
                <input type="submit" name="day" value="__Show selected__" />
            </p>
        </form>
    </fieldset>
    <fieldset>
        <form name="month" method="post" >
            <table class="std">
                <tr><th colspan="2">__Monthly logs__</th></tr>
                <!-- FOREACH month = $months -->
                    <tr class="light">
                        <td>$month.date</td>
                        <td><input type="radio" name="browse" value="$month.log"> __Select__</td>
                    </tr>
                <!-- ENDFOREACH -->
            </table>
            <p align="center">
<!-- ENDIF -->
                <input type="submit" name="month" value="__Show selected__" />
        </p>
    </form>
</fieldset>
