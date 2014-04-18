<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - CATEGORY TEMPLATE

die();?>
<script type="text/javascript">
    function checkForm(form) {
        var title = form.title.value;
        var access = form.access.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        var numRegex = /^[0-9]{1}$/;
        if (title == "") {
            inlineMsg('title', '[__Enter a title]');
            return false;
        }
        if (title.match(textRegex)) {
            inlineMsg('title', '[__You have used an invalid symbols]');
            return false;
        }
        if (access == "") {
            inlineMsg('access', '[__Enter a value of access level]');
            return false;
        }
        if (!access.match(numRegex)) {
            inlineMsg('access', '[__You have used an invalid value]');
            return false;
        }
        return true;
    }
</script>
<fieldset>
    <form id="form" name="form" method="post" action="" enctype="multipart/form-data" onsubmit="return checkForm(this);">
        <table class="std">
            <tr><th colspan="3">{header}</th></tr>
            <tr>
                <td class="label" style="width:15%;">[__Section]</td>
                <td colspan="2">
                [ifelse=section]
                    <input type="hidden" name="section" value="{section[id]}"/>
                    <input type="hidden" name="category" value="{id}"/>
                    <b>{section[title]}</b>
                [else]
                    <select name="section">
                    [each=sections]
                        <option value="{sections[id]}">{sections[title]}</option>
                    [endeach.sections]
                    </select>
                [endelse]
                </td>
            </tr>
            <tr>
                <td class="label">[__Title]</td>
                <td colspan="2"><input type="text" name="title" value="{title}" id="title" size="50" class="required" required="required" /></td>
            </tr>
            <tr><th colspan="4">[__Description]</th></tr>
            <tr><td class="bbcodes" colspan="3">{bbCodes}</td></tr>
            <tr><td colspan="3"><textarea id="desc" name="desc" cols="120" rows="5">{desc}</textarea></td></tr>
            <tr>
                <td class="label">[__Access level]</td>
                <td><input type="text" name="access" id="access" size="2" value="{access}" class="center required" required="required" /></td>
                <td class="help">[__It should be equal or more than the section access level]</td>
            </tr>
            <tr>
                <td class="label">[__Icon]</td>
            [ifelse=section]
                    <td colspan="2"><img src="{path}icon.png" width="35" height="35" alt="icon" /></td>
                </tr>
                <tr>
                    <td class="label">[__New icon]</td>
                    <td><input type="file" name="icon" size="60" value=""/></td>
            [else]
                    <td><input type="file" name="icon" size="60" value=""/></td>
            [endelse]
                <td class="help">[__Required fields have a yellow background]</td>
            </tr>
        </table>
        <p class="center">
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
