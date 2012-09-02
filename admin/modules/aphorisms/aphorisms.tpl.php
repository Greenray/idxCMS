<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION APHORISMS TEMPLATE

die();?>
<div class="module">[__Aphorisms]</div>
<fieldset>
<form name="config" method="post" action="">
    <table class="std">
        <tr class="odd">
            <td>[__File]</td>
            <td><input type="text" name="file" value="{file}" size="30" /></td>
        </tr>
        <tr class="odd"><td colspan="2"><textarea id="aph" name="aph" cols="80" rows="30">{aph}</textarea></td></tr>
    </table>
    <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
</form>
</fieldset>