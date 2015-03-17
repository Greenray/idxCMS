<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<script type="text/javascript">
    function checkForm(form) {
        var section = form.section.value;
        var title = form.title.value;
        var access = form.access.value;
        var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        var numRegex = /^[0-8]{1}$/;
        if (section === '') {
            ShowAlert('[__Enter a title]', '[__Error]');
            return false;
        }
        if (!section.match(nameRegex)) {
            ShowAlert('[__Invalid symbols]', '[__Error]');
            return false;
        }
        if (title === '') {
            ShowAlert('[__Enter a title]', '[__Error]');
            return false;
        }
        if (title.match(textRegex)) {
            ShowAlert('[__Invalid symbols]', '[__Error]');
            return false;
        }
        if (access === '') {
            ShowAlert('[__Enter a value of access level]', '[__Error]');
            return false;
        }
        if (!access.match(numRegex)) {
            ShowAlert('[__Enter a value of access level]', '[__Error]');
            return false;
        }
        return true;
    }
</script>
<fieldset>
    <form id="form" name="form" method="post" action="" onsubmit="return checkForm(this);">
        <table class="std">
            <tr><th colspan="3">{header}</th></tr>
            <tr>
                <td class="label">[__ID]</td>
                [ifelse=id]
                    <td colspan="2">
                        <input type="hidden" name="section" value="{id}" />
                        <input type="text" name="section" value="{id}" id="section" size="30" disabled="disabled" />
                    </td>
                [else]
                    <td><input type="text" name="section" value="" id="section" size="30" class="required" required="required" /></td>
                    <td class="help">[__Only latin characters, digits and symbol "_"]</td>
                [endelse]
                </tr>
            <tr>
                <td class="label">[__Title]</td>
                <td colspan="2"><input type="text" name="title" value="{title}" id="title" size="50" class="required" required="required" /></td>
            </tr>
            <tr><th colspan="4">[__Description]</th></tr>
            <tr><td class="bbcodes center" colspan="3">{bbCodes}</td></tr>
            <tr><td colspan="3"><textarea id="desc" name="desc" cols="130" rows="5">{desc}</textarea></td></tr>
            <tr>
                <td class="label">[__Access level]</td>
                <td><input type="text" name="access" id="access" size="2" value="{access}" class="center required" required="required" /></td>
                <td class="help">[__Required fields have a yellow background]</td>
            </tr>
        </table>
        <p class="center">
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
