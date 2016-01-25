<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: Tagcloud configuration template.

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
<div class="module">__Tagcloud__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr><th colspan="3">__General options__</th></tr>
            <tr class="light">
                <td>__Tagcloud width__</td>
                <td colspan="2"><input type="text" name="width" value="$width" size="4" /> px</td>
            </tr>
            <tr class="light">
                <td>__Tagcloud height__</td>
                <td><input type="text" name="height" value="$height" size="4" /> px</td>
                <td class="help">__In an ideal: 3/4 from width__</td>
            </tr>
            <tr class="light">
                <td>__Background__</td>
                <td>$bgcolor</td>
                <td class="help">__Background at the switched off transparency__</td>
            </tr>
            <tr class="light">
                <td>__Tags color__</td>
                <td>$color</td>
                <td class="help">__Leave a field empty for a multi-color mode__</td>
            </tr>
            <tr class="light">
                <td>__Color for gradient__</td>
                <td>$hicolor</td>
                <td class="help">__Leave a field empty for a multi-color mode__</td>
            </tr>
            <tr class="light">
                <td>__Flash-object transparency__</td>
                <td colspan="2"><input type="checkbox" name="wmode" value="1" <!-- IF !empty($wmode) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Rotation speed of the sphere__</td>
                <td><input type="text" name="speed" value="$speed" size="3" /> %</td>
                <td class="help">__Speed in percentage of established by default__</td>
            </tr>
            <tr class="light">
                <td>__Font__</td>
                <td><input type="text" name="style" value="$style" size="3" /> px</td>
                <td class="help">__For text mode__</td>
            </tr>
            <tr class="light">
                <td>__Number of tags__</td>
                <td><input type="text" name="tags" value="$tags" size="3" /></td>
                <td class="help">__Default__ = 20</td>
            </tr>
            <tr class="light">
                <td>__Placing of references on sphere__</td>
                <td><input type="checkbox" name="distr" value="1" <!-- IF !empty($distr) -->checked<!-- ENDIF --> /></td>
                <td class="help">__To place labels in regular intervals on the sphere area, differently - in a random way__</td>
            </tr>
        </table>
        <p class="center">
            <input type="submit" name="create" value="__Generate a tags file__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
<fieldset>
    <form name="tc_edit" method="post" action="">
        <table class="std">
            <tr><th colspan="3">__Tags__</th></tr>
            <tr class="light">
                <td class="center">__Tags__</td>
                <td class="center">__Quantity of mentions__</td>
                <td class="center">__Active__? </td>
            </tr>
            <!-- FOREACH tag = $used -->
                <tr class="light">
                    <td class="center"><input type="text" name="key[]" value="$tag.tag" /></td>
                    <td class="center"><input type="hidden" name="value[]" value="$tag.key" />$tag.key</td>
                    <td class="center"> + </td>
                </tr>
            <!-- ENDFOREACH -->
            <!-- FOREACH unused = $unused -->
                <tr class="light">
                    <td class="center"><input type="text" name="key[]" value="$unused.tag" /></td>
                    <td class="center"><input type="hidden" name="value[]" value="$unused.key" />$unused.key</td>
                    <td class="center"> - </td>
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="edit" value="__Save__" /></p>
    </form>
</fieldset>
