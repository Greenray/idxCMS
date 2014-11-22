<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - APHORISMA - SELECT FILE TEMPLATE

die();?>
<div class="module">[__Aphorisms]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td>[__Select file]</td>
                <td colspan="2">
                    <select name="aph" style="width:200px">
                        [foreach=aph.id.name]
                            <option value="{name}">{name}</option>
                        [endforeach.aph]
                    </select>
                </td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
