<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Guestbook
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Options]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Max size of database]</td>
                <td colspan="2"><input type="text" name="db-size" value="{db-size}" size="6" class="text" /> [__byte(s)]</td>
            </tr>
            <tr class="odd">
                <td>[__Max message length]</td>
                <td colspan="2"><input type="text" name="message-length" value="{message-length}" size="6" class="text" /> [__byte(s)]</td>
            </tr>
            <tr class="odd">
                <td>[__Messages per page]</td>
                <td colspan="2"><input type="text" name="per-page" value="{per-page}" size="4" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Allow guests to post]</td>
                <td colspan="2"><input type="checkbox" name="allow-guests-post" value="1" [if=allow-guests-post]checked="checked"[/if] /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
