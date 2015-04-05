<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Rights
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<fieldset>
    <form name="form1" method="post" action="">
    <table class="std">
        <tr><th colspan="3">[__Rights]</th></td></tr>
        <tr><td class="even center" colspan="3">[ifelse=dir][__Directory][else][__File][endelse]: <span class="special">{file}</span></td></tr>
        <tr>
            <td class="row1 center">[__Owner]</td>
            <td class="row1 center">[__Group]</td>
            <td class="row1 center">[__Other]</td>
        </tr>
        <tr>
            <td class="center">
                <input type="checkbox" name="rights[0]" value="r" [if=owner[r]]checked="checked"[endif] /> [__Reading]
                <input type="checkbox" name="rights[1]" value="w" [if=owner[w]]checked="checked"[endif] /> [__Writing]
                <input type="checkbox" name="rights[2]" value="x" [if=owner[x]]checked="checked"[endif] /> [__Executing]
            </td>
            <td class="center">
                <input type="checkbox" name="rights[3]" value="r" [if=group[r]]checked="checked"[endif] /> [__Reading]
                <input type="checkbox" name="rights[4]" value="w" [if=group[w]]checked="checked"[endif] /> [__Writing]
                <input type="checkbox" name="rights[5]" value="x" [if=group[x]]checked="checked"[endif] /> [__Executing]
            </td>
            <td class="center">
                <input type="checkbox" name="rights[6]" value="r" [if=other[r]]checked="checked"[endif] /> [__Reading]
                <input type="checkbox" name="rights[7]" value="w" [if=other[w]]checked="checked"[endif] /> [__Writing]
                <input type="checkbox" name="rights[8]" value="x" [if=other[x]]checked="checked"[endif] /> [__Executing]
            </td>
        </tr>
    </table>
    <p align="center">
        [if=dir]<input type="checkbox" name="recursively" value="1" /> [__Recursively]<br />[endif]
        <input type="hidden" name="file" value="{file}" />
        <input type="submit" name="save" value="[__Save]" class="submit" />
        <input type="reset" value="[__Reset]" class="submit" />
        <input type="submit" value="[__Back]" onclick="javascript:history.back();" class="submit" />
    </p>
    </form>
</fieldset>
