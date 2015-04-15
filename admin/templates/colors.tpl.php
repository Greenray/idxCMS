<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div id="colorselector" class="none" style="background:white;border:2px solid black;position:absolute;width:300px;z-index:10;">
    <table class="colortable">
        <tr><td class="center" colspan="18"><p>[__Choose color]</p></td></tr>
        <tr>
        [each=color]
            <td style="cursor: pointer; height:12px; width:12px; background:#{color[color]}" onclick="selectColor('#{color[color]}');"></td>
            [if=color[tr]]</tr><tr>[/if]
        [/each.color]
        </tr>
        <tr><td class="center" colspan="18"><p>[__Gradation of grey color]</p></td></tr>
        <tr>
        [each=gray]
            <td style="cursor: pointer; height:12px; width:12px; background:#{gray[gray]};" onclick="selectColor('#{gray[gray]}')"></td>
        [/each.gray]
        </tr>
    </table>
</div>
<input class="text" type="text" id="{name}" name="{name}" size="8" maxlength="8" value="{def_color}" />
<input id="{name}btn" name="{name}btn" type="button" value="" onclick="openColorSelector('{name}', event)" style="padding:0 5px;background:{def_color};" />
