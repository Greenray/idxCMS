<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Catalogs
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Options]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Description length]</td>
                <td><input type="text" name="description-length" value="{description-length}" size="6" class="text" /> [__symbols]</td>
                <td>[__If the description is not present, it will be generated from the basic text]</td>
            </tr>
            <tr class="odd">
                <td>[__Max comment length]</td>
                <td><input type="text" name="comment-length" value="{comment-length}" size="6" class="text" /> [__symbols]</td>
                <td>[__Not actual for admin]</td>
            </tr>
            <tr class="odd">
                <td>[__Items per page]</td>
                <td colspan="2"><input type="text" name="items-per-page" value="{items-per-page}" size="4" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Comments per page]</td>
                <td colspan="2"><input type="text" name="comments-per-page" value="{comments-per-page}" size="4" class="text" /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
