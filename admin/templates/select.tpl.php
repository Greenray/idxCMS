<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - APHORISMA - SELECT FILE TEMPLATE

die();?>

<div class="module">{title}</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Select file]</td>
                <td colspan="2">
                    <select name="selected" style="width:200px">
                        [foreach=select.id.name]
                            <option value="{name}">{name}</option>
                        [endforeach.select]
                    </select>
                </td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
