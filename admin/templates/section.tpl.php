<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Section template.

die();?>

<script type="text/javascript">
    function checkForm(form) {
        var section   = form.section.value;
        var title     = form.title.value;
        var access    = form.access.value;
        var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        var numRegex  = /^[0-9]{1}$/;
        if (section === '' || !section.match(nameRegex)) {
            ShowAlert('__Error in section ID__');
            return false;
        }
        if (title === '' || title.match(textRegex)) {
            ShowAlert('__Error in title__');
            return false;
        }
        if ((access === '') || !access.match(numRegex) || (access < 0) || (access > 9)) {
            ShowAlert('__Enter a value of access level__');
            return false;
        }
        return true;
    }
</script>
<fieldset>
    <form id="form" name="form" method="post" action="" onsubmit="return checkForm(this);">
        <table class="std">
            <tr><th colspan="3">$header</th></tr>
            <tr>
                <td class="label">ID</td>
                <!-- IF !empty($id) -->
                    <td colspan="2">
                        <input type="hidden" name="section" value="$id" />
                        <input type="text" name="section" value="$id" id="section" size="30" disabled="disabled" />
                    </td>
                <!-- ELSE -->
                    <td><input type="text" name="section" value="" id="section" size="30" class="required" /></td>
                    <td class="help">__Only latin characters, digits and symbol "_"__</td>
                <!-- ENDIF -->
                </tr>
            <tr>
                <td class="label">__Title__</td>
                <td colspan="2"><input type="text" name="title" value="$title" id="title" size="50" class="required" /></td>
            </tr>
            <tr><th colspan="4">__Description__</th></tr>
            <tr><td colspan="3" class="bbcodes center">$bbCodes</td></tr>
            <tr><td colspan="3"><textarea id="desc" name="desc" cols="130" rows="5">$desc</textarea></td></tr>
            <tr>
                <td class="label">__Access level__</td>
                <td><input type="text" name="access" id="access" size="2" value="$access" class="center required" /></td>
                <td class="help">__Required fields have a yellow background__</td>
            </tr>
        </table>
        <p class="center">
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
