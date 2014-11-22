<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - BACKUP TEMPLATE

die();?>

<div class="module">[__Backup]</div>
<fieldset>
    <form name="form" method="post" action="">
        <table class="std">
            <tr>
                <th>[__Title]</th>
                <th>[__Size]</th>
                <th>[__Actions]</th>
            </tr>
            [foreach=files.name.size]
                <tr class="odd">
                    <td>{name}</td>
                    <td class="center">{size} [__byte(s)]</td>
                    <td class="center">
                        <label><input type="checkbox" name="file[]" value="{name}" /> [__Delete]</label>
                        <a href="{BACKUPS}{name}"> [__Download]</a>
                    </td>
                </tr>
            [endforeach.files]
        </table>
        <p align="center">[__Total]: <span class="special">{total}</span></p>
        <p align="center">
            <input type="hidden" name="delete" value="1" />
            <input type="submit" value="[__Submit]" class="submit"/>
        </p>
    </form>
</fieldset>
<fieldset>
    <form name="form" method="post" action="">
        <table class="std">
            <tr><th colspan="3">[__Backup data]</th></tr>
            <tr>
                <th>[__Directory]</th>
                <th>[__Size]</th>
                <th>[__Actions]</th>
            </tr>
            [foreach=dirs.name.size]
            <tr class="odd">
                <td>{name}</td>
                <td class="right">{size}</td>
                <td class="center">
                    <label>
                        <input type="checkbox" name="dir[]" value="{name}" /> [__Select]
                    </label>
                </td>
            </tr>
            [endforeach.dirs]
        </table>
        <p align="center">
            <input type="hidden" name="backup" value="1" />
            <input type="submit" value="[__Submit]" class="submit"/>
        </p>
    </form>
</fieldset>
