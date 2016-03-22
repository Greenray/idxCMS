<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Category template

die();?>

<script type="text/javascript">
    function checkForm(form) {
        var title     = form.title.value;
        var access    = form.access.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        var numRegex  = /^[0-9]{1}$/;
        if (title === '' || title.match(textRegex)) {
            ShowAlert('__Error in title__');
            return false;
        }
        if ((access === '') || !access.match(numRegex) || (access < 0) || (access > 9)) {
            ShowAlert('__Error in value of the access level__');
            return false;
        }
        return true;
    }
</script>
<fieldset>
    <form id="form" name="form" method="post"  enctype="multipart/form-data" onsubmit="return checkForm(this);">
        <table class="std">
            <tr><th colspan="3">$header</th></tr>
            <tr>
                <td class="label" style="width:15%;">__Section__</td>
                <td colspan="2" class="category">
                <!-- IF !empty($section) -->
                    <!-- FOREACH section = $section -->
                        <input type="hidden" name="section" value="$section.id"/>
                        <input type="hidden" name="category" value="$id"/>
                        <b>$section.title</b>
                    <!-- ENDFOREACH -->
                <!-- ELSE -->
                    <select name="section">
                    <!-- FOREACH section = $sections -->
                        <option value="$section.id">$section.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                <!-- ENDIF -->
                </td>
            </tr>
            <tr>
                <td class="label">__Title__</td>
                <td colspan="2"><input type="text" name="title" value="$title" id="title" size="50" class="required" /></td>
            </tr>
            <tr><th colspan="4">__Description__</th></tr>
            <tr><td colspan="3" class="bbcodes center">$bbCodes</td></tr>
            <tr><td colspan="3"><textarea id="desc" name="desc" cols="120" rows="5">$desc</textarea></td></tr>
            <tr>
                <td class="label">__Access level__</td>
                <td><input type="text" name="access" id="access" size="2" value="$access" class="center required" /></td>
                <td class="help">__It should be equal or more than the section access level__</td>
            </tr>
            <!-- IF !empty($path) -->
                <tr>
                    <td class="label">__Icon__</td>
                    <td colspan="2"><img src="[$path:]icon.png" width="35" height="35" alt="icon" /></td>
                </tr>
            <!-- ENDIF -->
            <tr>
                <td class="label">__New icon__</td>
                <td><input type="file" name="icon" /></td>
                <td class="help">__Required fields have a yellow background__</td>
            </tr>
        </table>
        <p class="center">
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
