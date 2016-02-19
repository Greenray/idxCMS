<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Forum topic template.

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
<div class="module">__Topic__</div>
<fieldset>
    <form name="post" method="post" >
        <table class="std">
            <tr class="light">
                <td class="label">__Section__</td>
                <td><input type="hidden" name="section" value="$section_id" /><b>$section_title</b></td>
                <td class="label">__Select section__</td>
                <td>
                    <select name="new_section" onChange="setCategories(this.selectedIndex)">
                    <!-- FOREACH section = $sections -->
                        <option value="$section.id" <!-- IF !empty($section.selected) -->selected="selected"<!-- ENDIF -->>$section.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Category__</td>
                <td><input type="hidden" name="category" value="$category_id"/><b>$category_title</b></td>
                <td>__Select category__</td>
                <td>
                    <select name="new_category">
                    <!-- FOREACH category = $categories -->
                        <option value="$category.id" <!-- IF !empty($category.selected) -->selected="selected"<!-- ENDIF -->>$category.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Title__</td>
                <td colspan="3"><input type="text" name="title" value="$title" size="100" class="required" onfocus="if (this.value == '$title') {this.value = '';}" onblur="if (this.value == '') {this.value = '$title';}" /></td>
            </tr>
            <tr><th colspan="4">__Text__</th></tr>
            <tr><td colspan="4">$bbCodes_text</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" cols="80" rows="25">$text</textarea></td></tr>
            <tr class="light">
                <td>__Allow comments__</td>
                <td colspan="3"><input type="checkbox" name="opened" value="1" id="opened" <!-- IF !empty($opened) -->checked<!-- ENDIF --> /></td>
            </tr>
        </table>
        <p align="center">
            <input type="hidden" name="item" value="$topic" />
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
