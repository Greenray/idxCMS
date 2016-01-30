<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Feedbacks template.

die();?>

<div class="module">__Feedback requests__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <th colspan="3">__Messages__</th>
            <!-- FOREACH message = $messages -->
                <tr class="light"><td colspan="2">$message.time $message.info</td></tr>
                <tr class="light">
                    <td>$message.text</td>
                    <td><input type="checkbox" name="delete[]" value="$message.id" />__Delete__</td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="submit" value="__Submit__" /></p>
    </form>
</fieldset>
