<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Forum
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Options]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Max reply length]</td>
                <td><input type="text" name="reply-length" value="{reply-length}" size="6" class="text" /> [__symbols]</td>
                <td>[__Not actual for admin]</td>
            </tr>
            <tr class="odd">
                <td>[__Topics per page]</td>
                <td colspan="2"><input type="text" name="topics-per-page" value="{topics-per-page}" size="4" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Replies per page]</td>
                <td colspan="2"><input type="text" name="replies-per-page" value="{replies-per-page}" size="4" class="text" /></td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
