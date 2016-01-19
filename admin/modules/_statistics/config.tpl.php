<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Template for statistics configuration.

die();?>

<div class="module">__Configuration__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr><th colspan="3">__Users__</th></tr>
            <tr class="light">
                <td>__Register user browser__</td>
                <td colspan="2"><input type="checkbox" name="user_browser" value="1" <!-- IF !empty($user_browser) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr><th colspan="3">__Spiders__</th></tr>
            <tr class="light">
                <td>__Register spider IP__</td>
                <td colspan="2"><input type="checkbox" name="spider_ip" value="1" <!-- IF !empty($spider_ip) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Register spider agent__</td>
                <td colspan="2"><input type="checkbox" name="spider_agent" value="1" <!-- IF !empty($spider_agent) -->checked<!-- ENDIF --> /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
