<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Users]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <th colspan="3">[__Interaction with user]</th>
            <tr class="odd">
                <td>[__Email for users letters]</td>
                <td colspan="2"><input type="text" name="email" value="{email}" size="50" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Length for user name or nick]</td>
                <td colspan="2"><input type="text" name="nick-length" value="{nick-length}" size="4" class="text" /> [__symbols]</td>
            </tr>
            <tr class="odd">
                <td>[__Period when one password request can be acomplished]</td>
                <td colspan="2"><input type="text" name="flood" value="{flood}" size="50" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Timeout for rate]</td>
                <td colspan="2"><input type="text" name="timeout" value="{timeout}" size="4" class="text" /></td>
            </tr>
            <th colspan="3">[__Avatar]</th>
            <tr class="odd">
                <td>[__Image max width]</td>
                <td colspan="2"><input type="text" name="width" value="{width}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Image max height]</td>
                <td colspan="2"><input type="text" name="height" value="{height}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Max image size]</td>
                <td colspan="2"><input type="text" name="size" value="{size}" size="6" class="text" /> [__byte(s)]</td>
            </tr>
            <th colspan="3">[__Private messages]</th>
            <tr class="odd">
                <td>[__Max size of database]</td>
                <td colspan="2"><input type="text" name="db-size" value="{db-size}" size="6" class="text" /> [__byte(s)]</td>
            </tr>
            <tr class="odd">
                <td>[__Messages per page]</td>
                <td colspan="2"><input type="text" name="per-page" value="{per-page}" size="3" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Max message length]</td>
                <td colspan="2"><input type="text" name="message-length" value="{message-length}" size="10" class="text" /> [__byte(s)]</td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
