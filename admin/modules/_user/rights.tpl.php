<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="module">[__User profile]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr><th colspan="2">[__Rights for] {nick}</th></tr>
            <tr class="odd">
                <td>[__Access level]</td>
                <td><input type="text" name="access" id="access" value="{access}" class="required" /></td>
            </tr>
            <tr class="odd">
                <td>[__Administrator]</td>
                <td><input type="checkbox" name="root" value="{admin}" [if=admin]checked="checked"[endif] /></td>
            </tr>
            [each=rights]
                <tr class="odd">
                    <td>{rights[desc]}</td>
                    <td><input type="checkbox" name="rights[]" value="{rights[right]}" [if=rights[set]]checked="checked"[endif] /></td>
                </tr>
            [endeach.rights]
        </table>
        <p class="center">
            <input type="hidden" name="user" value="{user}" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
