<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die(); ?>
<fieldset>
    <form name="pm" method="post" action="">
        <table class="std">
            <th colspan="2">[__Administrative message for users]</th>
            <tr class="odd">
                <td>[__Select users]</td>
                <td>
                    <select name="users[]" size="10" multiple>
                        [each=users]<option value="{users[name]}">{users[nick]}</option>[endeach.users]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td colspan="2">
                    <p align="center">[__Text]</p>
                    {bbcodes}
                    <textarea id="text" name="text" cols="80" rows="7"></textarea>
                </td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="pm" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
<fieldset>
    <form name="letter" method="post" action="">
        <table class="std">
            <th colspan="2">[__Administrative letter for users]</th>
            <tr class="odd">
                <td>[__Select users]</td>
                <td>
                    <select name="users[]" size="10" multiple>
                        [each=users]<option value="{users[name]}">{users[nick]}</option>[endeach.users]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Subject]</td>
                <td><input type="text" name="subj" size="80" value="" /></td>
            </tr>
            <tr class="odd">
                <td colspan="2">
                    <p align="center">[__Text]</p>
                    <textarea id="text" name="text" cols="80" rows="7"></textarea>
                </td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="letter" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
