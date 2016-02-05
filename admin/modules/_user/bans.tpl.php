<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Bans managment template.

die();?>

<div class="module">__Bans__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <!-- FOREACH ban = $bans -->
                <tr class="light">
                    <td><input type="text" name="ban[]" value="$ban.ban" size="80" /></td>
                </tr>
            <!-- ENDFOREACH -->
            <tr class="light">
                <td><input type="text" name="ban[]" value="" size="80" /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
