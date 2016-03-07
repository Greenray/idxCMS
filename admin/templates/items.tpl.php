<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Items management

die();?>

<div class="module">$header</div>
<fieldset>
    <form name="item" method="post" >
        <table class="std">
            <tr class="dark">
                <td><input type="hidden" name="section" value="$section_id" />__Section__: <b>$section_title</b></td>
                <td colspan="8"><input type="hidden" name="category" value="$category_id" />__Category__: <b>$category_title</b></td>
            </tr>
            <tr>
                <th class="title">__Title__</th>
                <th class="author">__Date__</th>
                <th class="author">__Author__</th>
                <!-- IF ($section_id == files) -->
                    <th>__File__</th>
                    <th>__Size__</th>
                    <th>__Downloads__</th>
                <!-- ENDIF -->
                <!-- IF ($section_id == music) -->
                    <th>__File__</th>
                    <th>__Size__</th>
                    <th>__Downloads__</th>
                <!-- ENDIF -->
                <!-- IF ($section_id == links) -->
                    <th>__Site URL__</th>
                    <th>__Transitions__</th>
                <!-- ENDIF -->
                <th>__Views__</th>
                <th>__Comments__</th>
                <th class="actions">__Actions__</th>
            </tr>
            <!-- FOREACH item = $items -->
                <tr class="light">
                    <td style="padding:0 10px;">$item.title</td>
                    <td class="center">$item.date</td>
                    <td class="author">$item.nick</td>
                    <!-- IF !empty($item.file) -->
                        <td>$item.file</td>
                        <td class="right">$item.size</td>
                        <td class="center">$item.downloads</td>
                    <!-- ENDIF -->
                    <!-- IF !empty($item.music) -->
                        <td>$item.music</td>
                        <td class="right">$item.size</td>
                        <td class="center">$item.downloads</td>
                    <!-- ENDIF -->
                    <!-- IF $section_id == links -->
                        <td class="left">$item.site</td>
                        <td class="center">$item.clicks</td>
                    <!-- ENDIF -->
                    <td class="center">$item.views</td>
                    <td class="center">$item.comments</td>
                    <td class="actions center">
                        <button type="submit" name="$item.action" value="$item.id" class="icon icon-$item.action tip" title="$item.command"></button>
                        <button type="submit" name="edit" value="$item.id" class="icon icon-edit tip" title="__Edit__"></button>
                        <button type="submit" name="delete" value="$item.id" class="icon icon-delete tip" title="__Delete__"></button>
                    </td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="new" value="__New post__" /></p>
    </form>
</fieldset>
