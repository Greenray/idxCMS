<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Aphorisms template.

die();?>

<div class="module">__Aphorisms__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__File__</td>
                <td><input type="text" name="file" value="$file" size="30" /></td>
            </tr>
            <tr class="light"><td colspan="2"><textarea id="aph" name="aph" cols="80" rows="30">$aph</textarea></td></tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
