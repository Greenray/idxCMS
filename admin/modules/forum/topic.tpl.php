<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FORUM - TOPIC TEMPLATE

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
<div class="module">[__Topic]</div>
<fieldset>
    <form name="post" method="post" action="">
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
                <td colspan="3"><input type="text" name="title" value="{title}" size="100" class="required" onfocus="if (this.value == '{title}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{title}';}" required="required" /></td>
            </tr>
            <tr><th colspan="4">[__Text]</th></tr>
            <tr><td colspan="4">{bbCodes_text}</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" cols="80" rows="25">{text}</textarea></td></tr>
            <tr class="odd">
                <td>[__Comments]</td>
                <td colspan="3">
                    <input type="checkbox" name="opened" value="1" id="opened" [if=opened]checked="checked"[endif] />
                    <label for="opened"> [__Allow]</label>
                </td>
            </tr>
        </table>
        <input type="hidden" name="item" value="{topic}" />
        <p align="center">
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
