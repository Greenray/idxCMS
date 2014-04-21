<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - STATISTIC CONFIGURATION TEMPLATE

die();?>
<div class="module">[__Configuration]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <th colspan="3">[__Users]</th>
            <tr class="odd">
                <td>[__Register user browser]</td>
                <td colspan="2"><input type="checkbox" name="user-ua" value="1" [if=user-ua]checked="checked"[endif] /></td>
            </tr>
            <th colspan="3">[__Spiders]</th>
            <tr class="odd">
                <td>[__Register spider IP]</td>
                <td colspan="2"><input type="checkbox" name="spider-ip" value="1" [if=spider-ip]checked="checked"[endif] /></td>
            </tr>
            <tr class="odd">
                <td>[__Register spider agent]</td>
                <td colspan="2"><input type="checkbox" name="spider-ua" value="1" [if=spider-ua]checked="checked"[endif] /></td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
