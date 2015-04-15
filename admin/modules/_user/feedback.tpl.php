<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Feedback requests]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <th colspan="3">[__Messages]</th>
            [each=messages]
                <tr class="odd"><td colspan="2">{messages[time]} {messages[info]}</td></tr>
                <tr class="odd">
                    <td>{messages[text]}</td>
                    <td><input type="checkbox" name="delete[]" value="{messages[id]}" />[__Delete]</td>
                </tr>
            [/each.messages]
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
