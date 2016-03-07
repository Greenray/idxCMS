<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Form to select item

die();?>

<div class="module">$title</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__Select file__</td>
                <td colspan="2">
                    <select name="selected" style="width:200px">
                    <!-- FOREACH name = $select -->
                        <option value="$name._VAL_">$name._VAL_</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="submit" value="__Submit__" /></p>
    </form>
</fieldset>
