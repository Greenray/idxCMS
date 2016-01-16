<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Search keywords template.

die();?>

<div class="module">__Search keywords info__</div>
<fieldset>
    <table class="std">
        <tr>
            <th>__Search bots__</th>
            <th>__Keywords__</th>
            <th>__Page__</th>
            <th>__Counter__</th>
        </tr>
        <!-- FOREACH word = $words -->
            <tr class="light">
                <td>$word[0__</td>
                <td>$word[1__</td>
                <td>$word[2__</td>
                <td>$word.count</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
    <form name="clean" method="post" action="">
        <p align="center"><input type="submit" name="clean" value="__Clean__" /></p>
    </form>
</fieldset>
