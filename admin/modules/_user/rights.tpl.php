<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: User rights template.

die();?>

<div class="module">__User profile__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr><th colspan="2">__Rights for__ $nick</th></tr>
            <tr class="light">
                <td>__Access level__</td>
                <td><input type="text" name="access" value="$access" size="2" class="required" /></td>
            </tr>
            <tr class="light">
                <td>__Administrator__</td>
                <td><input type="checkbox" name="root" value="1" <!-- IF !empty($root) -->checked<!-- ENDIF --> /></td>
            </tr>
            <!-- FOREACH right = $rights -->
                <tr class="light">
                    <td>$right.desc</td>
                    <td><input type="checkbox" name="rights[]" value="$right.right" <!-- IF !empty($right.set) -->checked<!-- ENDIF --> /></td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p class="center">
            <input type="hidden" name="act" value="rights.save" />
            <input type="hidden" name="user" value="$user" />
            <input type="hidden" name="nick" value="$nick" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
