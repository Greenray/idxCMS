<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: RSS aggregator.

die();?>
<div class="module">__RSS aggregator__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__Cache timeout__</td>
                <td><input type="text" name="cache_time" value="$cache_time" size="6" /> __seconds__</td>
            </tr>
            <tr class="light">
                <td>__Max title length__</td>
                <td><input type="text" name="title_length" value="$title_length" size="6" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Max description length__</td>
                <td><input type="text" name="title_length" value="$title_length" size="4" /></td>
            </tr>
            <tr class="light">
                <td>__Feeds to aggregate (One URL per line)__</td>
                <td colspan="2"><textarea name="feeds" cols="20" rows="10">$feeds</textarea></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
