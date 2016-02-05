<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Minichat configuration template.

die();?>

<div class="module">__Options__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__Max size of database__</td>
                <td colspan="2"><input type="text" name="db_size" value="$db_size" size="6" /> __records__</td>
            </tr>
            <tr class="light">
                <td>__Max message length__</td>
                <td colspan="2"><input type="text" name="message_length" value="$message_length" size="6" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Messages to show__</td>
                <td colspan="2"><input type="text" name="mess_to_show" value="$mess_to_show" size="6" /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
