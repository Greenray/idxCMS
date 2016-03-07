<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Filemanager template.

die();?>

<div class="module">__Filemanager__</div>
<fieldset>
    <table class="std">
        <tr>
            <th width="48%">__File__</th>
            <th>__Size__</th>
            <th>__Date__</th>
            <th>__Time__</th>
            <th>__Rights__</th>
            <th class="actions" colspan="2">__Actions__</th>
        </tr>
        <!-- IF !empty($back) -->
            <tr><td colspan="7"><a href="$back">...</a></td></tr>
        <!-- ENDIF -->
        <!-- FOREACH element = $elements -->
            <tr>
            <!-- IF !empty($element.link) -->
                <td class="$element.style"><a href="$element.link">$element.file</a></td>
            <!-- ELSE -->
                <td class="$element.style">$element.file</td>
            <!-- ENDIF -->
                <td class="$element.style right">$element.size</td>
                <td class="$element.style center">$element.date</td>
                <td class="$element.style center">$element.time</td>
                <td class="$element.style"><a href="$element.rights_edit">$element.rights</a></td>
                <!-- IF !empty($element.download) -->
                    <td class="$element.style center"><a href="{BACKUPS}$element.file"> __Download__</a></td>
                <!-- ENDIF -->
                <!-- IF !empty($element.edit) -->
                    <td class="$element.style center"><a href="$element.edit">__Edit__</a></td>
                <!-- ENDIF -->
                <!-- IF !empty($element.view) -->
                    <td class="$element.style center"><a class="cbox" href="$element.view">__View__</a></td>
                <!-- ENDIF -->
                <!-- IF !empty($element.empty) -->
                    <td class="$element.style center"></td>
                <!-- ENDIF -->
                <td class="$element.style center"><a href="$element.delete">__Delete__</a></td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
    <table class="std">
        <tr>
            <td class="row2">
                <form name="form1" method="post" >
                    <input type="hidden" name="path" value="$path"/>
                    <input type="text" name="dirname" size="30"/>
                    <input type="submit" name="mkdir" value="__Make directory__"/>
                </form>
            </td>
            <td class="row3">
                <form name="form1" method="post" action="$url" enctype="multipart/form-data">
                    <input type="hidden" name="path" value="$path"/>
                    <input type="file" name="upload" size="30"/>
                    <input type="submit" name="test" value="__Upload__"/>
                </form>
            </td>
        </tr>
    </table>
    <div class="center">
        <p><input type="button" value="__Back__" onclick="javascript:history.back();" /></p>
    </div>
</fieldset>
