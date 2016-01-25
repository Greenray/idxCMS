<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Template for color picker

die();?>

<div id="colorselector" style="display:none;background:white;border:2px solid black;position:absolute;width:300px;z-index:10;">
    <table class="colortable">
        <tr><td class="center" colspan="18"><p>__Choose color__</p></td></tr>
        <tr>
        <!-- FOREACH color = $colors -->
            <td style="cursor: pointer; height:12px; width:12px; background:#$color.color" onclick="selectColor('#$color.color');"></td>
            <!-- IF !empty($color.tr) --></tr><tr><!-- ENDIF -->
        <!-- ENDFOREACH -->
        </tr>
        <tr><td class="center" colspan="18"><p>__Gradation of grey color__</p></td></tr>
        <tr>
        <!-- FOREACH gray = $gray -->
            <td style="cursor: pointer; height:12px; width:12px; background:#$gray.gray;" onclick="selectColor('#$gray.gray')"></td>
        <!-- ENDFOREACH -->
        </tr>
    </table>
</div>
<input id="$name" name="$name" type="text" value="$def_color" size="8" maxlength="8" />
<input id="[$name:]btn" name="[$name:]btn" type="button" value="" onclick="openColorSelector('$name', event)" style="padding:0 5px;background:$def_color;" />
