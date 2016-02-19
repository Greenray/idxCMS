<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Users configuration template.

die();?>

<div class="module">__Users__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <th colspan="3">__Interaction with user__</th>
            <tr class="light">
                <td>__Email for users letters__</td>
                <td colspan="2"><input type="text" name="email" value="$email" size="50" /></td>
            </tr>
            <tr class="light">
                <td>__Max length for user name or nick__</td>
                <td colspan="2"><input type="text" name="nick_length" value="$nick_length" size="4" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Period when one password request can be acomplished__</td>
                <td colspan="2"><input type="text" name="flood" value="$flood" size="4" /> __seconds__</td>
            </tr>
            <tr class="light">
                <td>__Timeout for rate__</td>
                <td colspan="2"><input type="text" name="timeout" value="$timeout" size="4" /> __seconds__</td>
            </tr>
            <th colspan="3">__Avatar__</th>
            <tr class="light">
                <td>__Image max width__</td>
                <td colspan="2"><input type="text" name="width" value="$width" size="6" /> px</td>
            </tr>
            <tr class="light">
                <td>__Image max height__</td>
                <td colspan="2"><input type="text" name="height" value="$height" size="6" /> px</td>
            </tr>
            <tr class="light">
                <td>__Max image size__</td>
                <td colspan="2"><input type="text" name="size" value="$size" size="6" /> __byte(s)__</td>
            </tr>
            <th colspan="3">__Private messages__</th>
            <tr class="light">
                <td>__Max size of database__</td>
                <td colspan="2"><input type="text" name="db_size" value="$db_size" size="4" /> __records__</td>
            </tr>
            <tr class="light">
                <td>__Messages per page__</td>
                <td colspan="2"><input type="text" name="per_page" value="$per_page" size="3" /> __records__</td>
            </tr>
            <tr class="light">
                <td>__Max message length__</td>
                <td colspan="2"><input type="text" name="message_length" value="$message_length" size="4" /> __byte(s)__</td>
            </tr>
            <th colspan="3">__Feedback__</th>
            <tr class="light">
                <td>__Max size of database__</td>
                <td colspan="2"><input type="text" name="db_size" value="$db_size" size="4" /> __records__</td>
            </tr>
            <tr class="light">
                <td>__Max message length__</td>
                <td colspan="2"><input type="text" name="message_length" value="$message_length" size="4" /> __byte(s)__</td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
