<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FILEMANAGER TEMPLATE

die();?>
<div class="module">[__Filemanager]</div>
<fieldset>
    <table class="std">
        <tr>
            <th width="48%">[__File]</th>
            <th>[__Size]</th>
            <th>[__Date]</th>
            <th>[__Time]</th>
            <th>[__Rights]</th>
            <th class="actions" colspan="2">[__Actions]</th>
        </tr>
        [if=back]<tr><td colspan="7"><a href="{back}">..</a></td></tr>[endif]
        [each=elements]
            <tr>
                [ifelse=elements[link]]
                    <td class="{elements[style]}"><a href="{elements[link]}">{elements[file]}</a></td>
                [else]
                    <td class="{elements[style]}">{elements[file]}</td>
                [endelse]
                <td class="{elements[style]} right">{elements[size]}</td>
                <td class="{elements[style]} center">{elements[date]}</td>
                <td class="{elements[style]} center">{elements[time]}</td>
                <td class="{elements[style]}"><a href="{elements[rights_edit]}">{elements[rights]}</a></td>
                [if=elements[download]]<td class="{elements[style]} center"><a href="{BACKUPS}{elements[file]}"> [__Download]</a></td>[endif]
                [if=elements[edit]]<td class="{elements[style]} center"><a href="{elements[edit]}">[__Edit]</a></td>[endif]
                [if=elements[view]]<td class="{elements[style]} center"><a class="cbox" href="{elements[view]}">[__View]</a></td>[endif]
                [if=elements[empty]]<td class="{elements[style]} center"></td>[endif]
                <td class="{elements[style]} center"><a href="#" {elements[alert]}>[__Delete]</a></td>
            </tr>
        [endeach.elements]
    </table>
    <table class="std">
        <tr>
            <td class="row2">
                <form name="form1" method="post" action="">
                    <input type="hidden" name="path" value="{path}"/>
                    <input type="text" name="dirname" size="30"/>
                    <input type="submit" name="mkdir" value="[__Make directory]"/>
                </form>
            </td>
            <td class="row3">
                <form name="form1" method="post" action="{url}" enctype="multipart/form-data">
                    <input type="hidden" name="path" value="{path}"/>
                    <input type="file" name="upload" size="30"/>
                    <input type="submit" name="test" value="[__Upload]"/>
                </form>
            </td>
        </tr>
    </table>
    <div class="center"><p><input type="button" value="[__Back]" onclick="javascript:history.back();" class="submit" /></p></div>
</fieldset>
