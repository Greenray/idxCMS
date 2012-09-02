<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FEEDBACK TEMPLATE

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
            [endeach.messages]
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
