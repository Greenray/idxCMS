<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Forum configuration template.

die();?>

<div class="module">__Options__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__Max reply length__</td>
                <td><input type="text" name="message_length" value="$message_length" size="6" /> __symbols__</td>
                <td class="help">__Not actual for admin__</td>
            </tr>
            <tr class="light">
                <td>__Topics per page__</td>
                <td colspan="2"><input type="text" name="topics_per_page" value="$topics_per_page" size="4" /></td>
            </tr>
            <tr class="light">
                <td>__Replies per page__</td>
                <td colspan="2"><input type="text" name="replies_per_page" value="$replies_per_page" size="4" /></td>
            </tr>
        </table>
        <p align="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
