<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Backups template.

die();?>

<div class="module">__Backup__</div>
<fieldset>
    <form name="form" method="post" >
        <table class="std">
            <tr>
                <th>__Title__</th>
                <th>__Size__</th>
                <th>__Actions__</th>
            </tr>
            <!-- FOREACH backup = $backups -->
                <tr class="light">
                    <td>$backup.name</td>
                    <td class="center">$backup.size __byte(s)__</td>
                    <td class="center">
                        <input type="checkbox" name="backups[]" value="$backup.name" /> __Delete__
                        <a href="{BACKUPS}$backup.name"> __Download__</a>
                    </td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p align="center">__Total__: <span class="special">$total</span></p>
        <p align="center">
            <input type="hidden" name="delete" value="1" />
            <input type="submit" name="save" value="__Submit__" />
        </p>
    </form>
</fieldset>
<fieldset>
    <form name="form" method="post" >
        <table class="std">
            <tr><th colspan="3">__Backup data__</th></tr>
            <tr>
                <th>__Directory__</th>
                <th>__Size__</th>
                <th>__Actions__</th>
            </tr>
            <!-- FOREACH dir = $dirs -->
                <tr class="light">
                    <td>$dir.name</td>
                    <td class="right">$dir.size</td>
                    <td class="center"><input type="checkbox" name="dir[]" value="$dir.name" /> __Select__</td>
                </tr>
            <!-- ENDFOREACH -->
            <!-- FOREACH file = $files -->
                <tr class="light">
                    <td>$file.name</td>
                    <td class="right">$file.size</td>
                    <td class="center"><input type="checkbox" name="file[]" value="$file.name" /> __Select__</td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p align="center"><input type="submit" name="save" value="__Submit__" /></p>
    </form>
</fieldset>
