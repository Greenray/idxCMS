<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script type="text/javascript">
    var ids = new Array({ids});
    var titles = new Array({titles});
    function getIds(index) {
        var idsValues = ids[index];
        return idsValues.split(',');
    }
    function getTitles(index) {
        var titlesText = titles[index];
        return titlesText.split(',');
    }
    function setCategories(index) {
        var idsList = getIds(index);
        var idsListCount = idsList.length;
        var titlesList = getTitles(index);
        var categoriesList = document.forms['editor'].elements['new_category'];
        categoriesList.length = 0;
        for (i = 0; i < idsListCount; i++) {
            if (document.createElement) {
                var newCategoriesList = document.createElement('OPTION');
                newCategoriesList.text = titlesList[i];
                newCategoriesList.value = idsList[i];
                (categoriesList.options.add) ? categoriesList.options.add(newCategoriesList) : categoriesList.add(newCategoriesList, null);
            } else {
                newCategoriesList.options[i] = new Option(idsList[i], titlesList[i], false, false);
            }
        }
    }
    setCategories(document.forms['editor'].elements['new_section'].selectedIndex);
    function checkPost(form) {
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
            ShowAlert('[__Enter a text]', '[__Error]');
            return false;
        }
        return true;
    }
</script>
[ifelse=admin]
[else]
    <div class="center">[__Post]</div>
    <div class="center">[__Your article will be published after premoderation]</div>
[endelse]
<form name="editor" method="post" action="" onsubmit="return checkPost(this);">
    <fieldset>
    <legend>{header}</legend>
        <table>
            <tr class="odd">
                <td class="label">[__Section]</td>
                [ifelse=admin]
                    <td><input type="hidden" name="section" value="{section_id}" /><b>{section_title}</b></td>
                    <td class="label">[__Select section]</td>
                    <td>
                        <select name="new_section" onChange="setCategories(this.selectedIndex)">
                            [each=sections]<option value="{sections[id]}" [if=sections[selected]]selected="selected"[endif]>{sections[title]}</option>[endeach.sections]
                        </select>
                    </td>
                [else]
                    <td colspan="3"><b>{section_title}</b></td>
                [endelse]
            </tr>
            <tr class="odd">
                <td class="label">[__Category]</td>
                [ifelse=admin]
                    <td><input type="hidden" name="category" value="{category_id}"/><b>{category_title}</b></td>
                    <td class="label">[__Select category]</td>
                    <td>
                        <select name="new_category">
                            [each=categories]<option value="{categories[id]}" [if=categories[selected]]selected="selected"[endif]>{categories[title]}</option>[endeach.categories]
                        </select>
                    </td>
                [else]
                    <td colspan="3"><b>{category_title}</b></td>
                [endelse]
            </tr>
            <tr class="odd">
                <td class="label">[__Title]</td>
                <td colspan="3"><input type="text" name="title" value="{title}" id="title" size="60" class="required" required="required" /></td>
            </tr>
            <tr class="odd">
                <td class="label">[__Keywords]</td>
                <td colspan="3"><input type="text" id="keywords" name="keywords"  size="50"  value="{keywords}" /></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="center">
                        <p>
                            <a href="#post" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                                [__Description]
                            </a>
                        </p>
                    </div>
                    <div id="shdesc" class="none">
                        {bbCodes_desc}
                        <div class="center"><textarea id="desc" name="desc" cols="80" rows="5" >{desc}</textarea></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="center">[__Text]</div>
                    {bbCodes_text}
                    <div class="center"><textarea id="text" name="text" cols="80" rows="25">{text}</textarea></div>
                </td>
            </tr>
            [ifelse=admin]
                <tr class="odd">
                    <td>[__Comments]: <input type="checkbox" name="opened" value="1" id="opened" [if=opened]checked="checked"[endif] /><label for="opened"> [__Allow]</label></td>
                    <td colspan="3" >&nbsp;</td>
                </tr>
            [else]
                <input type="hidden" name="opened" value="1" />
            [endelse]
        </table>
        <p class="center">
            <input type="hidden" name="item" value="{item}" />
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </fieldset>
</form>
