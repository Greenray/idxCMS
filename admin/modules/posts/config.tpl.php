<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Publications and news configuration template.

die();?>

<div class="module">__Options__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="light">
                <td>__Description length__</td>
                <td><input type="text" name="description_length" value="$description_length" size="6" /> __symbols__</td>
                <td class="help">__If the description is not present, it will be generated from the basic text__</td>
            </tr>
            <tr class="light">
                <td>__Max comment length__</td>
                <td><input type="text" name="message_length" value="$message_length" size="6" /> __symbols__</td>
                <td class="help">__Not actual for admin__</td>
            </tr>
            <tr class="light">
                <td>__Posts per page__</td>
                <td colspan="2"><input type="text" name="posts_per_page" value="$posts_per_page" size="4" /></td>
            </tr>
            <tr class="light">
                <td>__Comments per page__</td>
                <td colspan="2"><input type="text" name="comments_per_page" value="$comments_per_page" size="4" /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
