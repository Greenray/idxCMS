<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE POSTS - POST FORM

die();?>
<script type="text/javascript">
    var ids = new Array({ids});
    var titles = new Array({titles});
    function getIds(index) {
        var idsValues = ids[index];
        return idsValues.split(",");
    }
    function getTitles(index) {
        var titlesText = titles[index];
        return titlesText.split(",");
    }
    function setCategories(index) {
        var idsList = getIds(index);
        var idsListCount = idsList.length;
        var titlesList = getTitles(index);
        var categoriesList = document.forms["post"].elements["new_category"];
        var categoriesListCount = categoriesList.options.length;
        categoriesList.length = 0;
        for (i = 0; i < idsListCount; i++) {
            if (document.createElement) {
                var newCategoriesList = document.createElement("OPTION");
                newCategoriesList.text = titlesList[i];
                newCategoriesList.value = idsList[i];
                (categoriesList.options.add) ? categoriesList.options.add(newCategoriesList) : categoriesList.add(newCategoriesList, null);
            } else {
                // M3.x-4.x
                newCategoriesList.options[i] = new Option(idsList[i], titlesList[i], false, false);
            }
        }
    }
    setCategories(document.forms["post"].elements["new_section"].selectedIndex);
</script>
<script type="text/javascript">
    function checkPost(form) {
        var title = form.title.value;
        var text = form.text.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        if (title == "") {
            inlineMsg('title', '[__Enter a title]');
            return false;
        }
        if (title.match(textRegex)) {
            inlineMsg('title', '[__You have used an invalid symbols]');
            return false;
        }
        if (text == "") {
            inlineMsg('title', '[__Enter a text]');
            return false;
        }
        return true;
    }
</script>
<form name="post" method="post" action="" onsubmit="return checkPost(this);">
    <fieldset>
    <legend>{header}</legend>
        <table class="std">
            <tr class="odd">
                <td class="label">[__Section]</td>
                <td><input type="hidden" name="section" value="{section_id}" /><b>{section_title}</b></td>
                <td class="label">[__Select section]</td>
                <td>
                    <select name="new_section" onChange="setCategories(this.selectedIndex)">
                        [each=sections]
                            <option value="{sections[id]}" [if=sections[selected]]selected="selected"[endif]>{sections[title]}</option>
                        [endeach.sections]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Category]</td>
                <td><input type="hidden" name="category" value="{category_id}"/><b>{category_title}</b></td>
                <td>[__Select category]</td>
                <td>
                    <select name="new_category">
                        [each=categories]
                            <option value="{categories[id]}" [if=categories[selected]]selected="selected"[endif]>{categories[title]}</option>
                        [endeach.categories]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Title]</td>
                <td colspan="3"><input type="text" name="title" value="{title}" id="title" size="60" class="required" required="required" /></td>
            </tr>
            <tr class="odd">
                <td>[__Keywords]</td>
                <td colspan="3"><input type="text" name="keywords" value="{keywords}" size="60" class="text" /></td>
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
                    <div id="shdesc" style="display:none;">
                        <div>{bbCodes_desc}</div>
                        <div><textarea id="desc" name="desc" cols="80" rows="5">{desc}</textarea></div>
                    </div>
                </td>
            </tr>
            <tr class="odd"><th colspan="4">[__Text]</th></tr>
            <tr><td colspan="4">{bbCodes_text}</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" cols="80" rows="25">{text}</textarea></td></tr>
            <tr class="odd">
                <td>[__Comments]</td>
                <td colspan="3">
                    <input type="checkbox" name="opened" value="1" [if=opened]checked="checked"[endif] />
                    <label for="opened"> [__Allow]</label>
                </td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="item" value="{item}" />
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </fieldset>
</form>
