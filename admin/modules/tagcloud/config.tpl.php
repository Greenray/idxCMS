<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Tagcloud
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script>
    var curselectorinput;
    function selectColor(color) {
        document.getElementById(curselectorinput).value = color;
        if (document.all) {
            document.getElementById(curselectorinput + "btn").style.background = color;
        } else {
            if (document.getElementById) {
                document.getElementById(curselectorinput + "btn").style.background = color;
            }
        }
        closeColorSelector();
    }
    function openColorSelector(o, e) {
        selecto = document.getElementById("colorselector").style;
        if (selecto.display === "block") {
            closeColorSelector();
        } else {
            selecto.display = "block";
            if (document.all && typeof(window.opera) !== "object") {
                selecto.left = event.x + document.body.scrollLeft - 420;
                selecto.top  = event.y + document.body.scrollTop - 120;
            } else {
                if (document.getElementById) {
                    selecto.left = (e.clientX + window.pageXOffset - 440) + "px";
                    selecto.top = (e.clientY + window.pageYOffset - 120) + "px";
                }
            }
            curselectorinput = o;
        }
    }
    function closeColorSelector() {
        document.getElementById("colorselector").style.display = "none";
    }
</script>
<div class="module">[__Tagcloud]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr><th colspan="3">[__General options]</th></tr>
            <tr class="odd">
                <td>[__Tagcloud width]</td>
                <td colspan="2"><input type="text" name="width" value="{width}" size="4" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Tagcloud height]</td>
                <td><input type="text" name="height" value="{height}" size="4" /> px</td>
                <td>[__In an ideal: 3/4 from width]</td>
            </tr>
            <tr class="odd">
                <td>[__Background]</td>
                <td>{bgcolor}</td>
                <td>[__Background at the switched off transparency]</td>
            </tr>
            <tr class="odd">
                <td>[__Tags color]</td>
                <td>{color}</td>
                <td>[__Leave a field empty for a multi-color mode]</td>
            </tr>
            <tr class="odd">
                <td>[__Color for gradient]</td>
                <td>{hicolor}</td>
                <td>[__Leave a field empty for a multi-color mode]</td>
            </tr>
            <tr class="odd">
                <td>[__Flash-object transparency]</td>
                <td colspan="2"><input type="checkbox" name="wmode" value="1" [if=wmode]checked="checked"[/if] /></td>
            </tr>
            <tr class="odd">
                <td>[__Rotation speed of the sphere]</td>
                <td><input type="text" name="speed" value="{speed}" size="3" /> %</td>
                <td>[__Speed in percentage of established by default]</td>
            </tr>
            <tr class="odd">
                <td>[__Font]</td>
                <td><input type="text" name="style" value="{style}" size="3" /> px</td>
                <td>[__For text mode]</td>
            </tr>
            <tr class="odd">
                <td>[__Number of tags]</td>
                <td><input type="text" name="tags" value="{tags}" size="3" /></td>
                <td>[__Default] = 20</td>
            </tr>
            <tr class="odd">
                <td>[__Placing of references on sphere]</td>
                <td><input type="checkbox" name="distr" value="1" [if=distr]checked="checked"[/if] /></td>
                <td>[__To place labels in regular intervals on the sphere area, differently - in a random way]</td>
            </tr>
        </table>
        <p class="center">
            <input type="submit" name="create" value="[__Generate a tags file]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
<fieldset>
    <form name="tc_edit" method="post" action="">
        <table class="std">
            <tr><th colspan="3">[__Tags]</th></tr>
            <tr class="odd">
                <td class="center">[__Tags]</td>
                <td class="center">[__Quantity of mentions]</td>
                <td class="center">[__Active]? </td>
            </tr>
            [foreach=used.key.tag]
                <tr class="odd">
                    <td class="center"><input type="text" name="key[]" value="{key}" /></td>
                    <td class="center"><input type="hidden" name="value[]" value="{tag}" />{tag}</td>
                    <td class="center"> + </td>
                </tr>
            [/foreach.used]
            [foreach=unused.key.tag]
                <tr class="odd">
                    <td class="center"><input type="text" name="key[]" value="{key}" /></td>
                    <td class="center"><input type="hidden" name="value[]" value="{tag}" />{tag}</td>
                    <td class="center"> - </td>
                </tr>
            [/foreach.unused]
        </table>
        <p class="center"><input type="submit" name="edit" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
