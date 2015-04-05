<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - RSS
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__RSS aggregator]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Cache timeout]</td>
                <td><input type="text" name="cache-time" value="{cache-time}" size="6" class="text" /> [__seconds]</td>
            </tr>
            <tr class="odd">
                <td>[__Max title length]</td>
                <td><input type="text" name="title-length" value="{title-length}" size="6" class="text" /> [__symbols]</td>
            </tr>
            <tr class="odd">
                <td>[__Max description length]</td>
                <td><input type="text" name="title-length" value="{title-length}" size="4" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Feeds to aggregate (One URL per line)]</td>
                <td colspan="2"><textarea name="feeds" cols="20" rows="10">{feeds}</textarea></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
