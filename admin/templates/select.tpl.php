<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">{title}</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Select file]</td>
                <td colspan="2">
                    <select name="selected" style="width:200px">
                        [foreach=select.id.name]
                        <option value="{name}">{name}</option>
                        [/foreach.select]
                    </select>
                </td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
