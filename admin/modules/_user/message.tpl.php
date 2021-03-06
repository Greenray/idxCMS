<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Messages template.

die(); ?>

<fieldset>
    <form name="pm" method="post" >
        <table class="std">
            <th colspan="2">__Administrative message__</th>
            <tr class="light">
                <td class="label">__Select users__</td>
                <td>
                    <select name="users[]" size="5" multiple>
                    <!-- FOREACH user = $users -->
                        <option value="$user.name">$user.nick</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td colspan="2" class="center">
                    <p>__Text__</p>
                    $bbcodes
                    <textarea id="text" name="text" rows="10"></textarea>
                </td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="pm" value="__Submit__" /></p>
    </form>
</fieldset>
<fieldset>
    <form name="letter" method="post" >
        <table class="std">
            <th colspan="2">__Administrative letter__</th>
            <tr class="light">
                <td class="label">__Select users__</td>
                <td>
                    <select name="users[]" size="5" multiple>
                    <!-- FOREACH user = $users -->
                        <option value="$user.name">$user.nick</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Subject__</td>
                <td><input type="text" name="subj" size="80" value="" /></td>
            </tr>
            <tr class="light">
                <td colspan="2" class="center">
                    <p>__Text__</p>
                    <textarea id="text" name="text" rows="10"></textarea>
                </td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="letter" value="__Submit__" /></p>
    </form>
</fieldset>
