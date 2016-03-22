<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Search user template.

die();?>

<div class="module">__User profile__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light">
                <td>__Enter username or mask of usernames__</td>
                <td><input type="text" name="search" value="" size="30" /></td>
                <td class="help">__For example__: *, vas*, vasia</td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="act" value="search" />
            <input type="submit" name="submit" value="__Submit__" />
        </p>
    </form>
</fieldset>
