<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Catalogs
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script type="text/javascript">
    function checkLink(form) {
        var title = form.title.value;
        var text = form.text.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        if (title === '') {
            ShowAlert('[__Enter a title]', '[__Error]');
            return false;
        }
        if (title.match(textRegex)) {
            ShowAlert('[__Invalid symbols]', '[__Error]');
            return false;
        }
        if (text === '') {
            ShowAlert('[__Enter a description]', '[__Error]');
            return false;
        }
        return true;
    }
</script>
<div class="module">[__Link]</div>
<fieldset>
    <form name="item" method="post" action="" enctype="multipart/form-data" onsubmit="return checkLink(this);">
        <table class="std">
            <tr class="odd">
            [ifelse=category_id]
                <td>[__Category]</td>
                <td><input type="hidden" name="category" value="{category_id}"/><b>{category_title}</b></td>
                <td>[__Select category]</td>
                <td>
            [else]
                <td>[__Select category]</td>
                <td colspan="3">
            [endelse]
                    <select name="new_category">
                        [each=categories]<option value="{categories[id]}" [if=categories[selected]]selected="selected"[endif]>{categories[title]}</option>[endeach.categories]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Title]</td>
                <td colspan="3"><input type="text" name="title" value="{title}" id="title" size="100" class="required" required="required" /></td>
            </tr>
            <tr class="odd">
                <td>[__Keywords]</td>
                <td colspan="3"><input type="text" name="keywords" value="{keywords}" size="100" class="text" /></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="center">
                        <p>
                            <a href="#item" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                                [__Description]
                            </a>
                        </p>
                    </div>
                    <div id="shdesc" class="none">
                        {bbCodes_desc}
                        <textarea id="desc" name="desc" rows="5" >{desc}</textarea>
                    </div>
                </td>
            </tr>
            <tr><th colspan="4">[__Text]</th></tr>
            <tr><td colspan="4">{bbCodes_text}</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" id="text" rows="15">{text}</textarea></td></tr>
            <tr class="odd">
                <td>[__Link]</td>
                <td colspan="3"><input type="text" name="site" value="{site}" size="50" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Copyright]</td>
                <td colspan="3">&copy; <input type="text" name="copyright" value="{copyright}" size="50" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Comments]</td>
                <td colspan="3">
                    <input type="checkbox" name="opened" value="1" [if=opened]checked="checked"[endif] />
                    <label for="opened"> [__Allow]</label>
                </td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="item" value="{id}" />
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
