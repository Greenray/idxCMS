<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - MODULES TEMPLATE

die();?>
<div class="module">[__Modules]</div>
<fieldset>
    <form name="form" method="post" action="">
        <table class="std">
            <tr><th colspan="2">[__Module]</th><th>[__Status]</th></tr>
            [each=modules]
                [ifelse=modules[system]]
                    <tr class="even">
                        <td>{modules[title]}</td>
                        <td>[__System module]</td>
                        <td class="center"><input type="hidden" name="enable[{modules[module]}]" value="1" />[__Enabled]</td>
                    </tr>
                [else]
                    <tr class="odd">
                        <td colspan="2">{modules[title]}</td>
                        <td class="center"><label><input type="checkbox" name="enable[{modules[module]}]" value="1" [if=module[enabled]]checked="checked"[endif] /> [__Enable]</label></td>
                    </tr>
                [endelse]
                [each=modules[ext]]
                    <tr class="{ext[class]}">
                        <td>&emsp;&mdash; {ext[title]}</td>
                        [ifelse=ext[system]]
                            <td> [__System module extension]</td>
                            <td class="center"><input type="hidden" name="enable[{ext[module]}]" value="1" />[__Enabled]</td>
                        [else]
                            <td> [__Module extension]</td>
                            <td class="center">
                                <label><input type="checkbox" name="enable[{ext[module]}]" value="1" {ext[checked]} /> [__Enable]</label>
                            </td>
                        [endelse]
                    </tr>
                [endeach.modules[ext]]
            [endeach.modules]
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
