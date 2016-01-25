<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Post article template.

die();?>

<script type="text/javascript">
    var ids    = new Array({ids});
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
        var categoriesList = document.forms['post'].elements['new_category'];
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
//    setCategories(document.forms['post'].elements['new_section'].selectedIndex);
    function checkPost(form) {
        var title = form.title.value;
        var text = form.text.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        if (title === '' || title.match(textRegex)) {
            ShowAlert('__Error in title__');
            return false;
        }
        if (text === '') {
            ShowAlert('__Enter the text__');
            return false;
        }
        return true;
    }
</script>
<div class="module">__Post__</div>
<fieldset>
    <form name="post" method="post" action="" onsubmit="return checkPost(this);">
        <table class="std">
            <tr class="light">
                <td class="label">__Section__</td>
                <td style="width:550px"><b>$section_title</b></td>
                <td class="label" style="width:150px" >__Select section__</td>
                <td>
                    <select name="new_section" onChange="setCategories(this.selectedIndex)">
                    <!-- FOREACH section = $sections -->
                        <option value="$section.id" <!-- IF !empty($section.selected) -->selected<!-- ENDIF -->>$section.title</option>
                     <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Category__</td>
                <td><b>$category_title</b></td>
                <td>__Select category__</td>
                <td>
                    <select name="new_category">
                    <!-- FOREACH category = $categories -->
                        <option value="$category.id" <!-- IF !empty($category.selected) -->selected<!-- ENDIF -->>$category.title</option>
                     <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Title__</td>
                <td colspan="3"><input type="text" name="title" value="$title" id="title" size="100" class="required" /></td>
            </tr>
            <tr class="light">
                <td>__Keywords__</td>
                <td colspan="3"><input type="text" name="keywords" value="$keywords" size="100" /></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="center">
                        <p>
                            <a href="#post" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                                __Description__
                            </a>
                        </p>
                    </div>
                    <div id="shdesc" style="display:none;">
                        <div>$bbCodes_desc</div>
                        <div><textarea id="desc" name="desc" cols="80" rows="5">$desc</textarea></div>
                    </div>
                </td>
            </tr>
            <tr><th colspan="4">__Text__</th></tr>
            <tr><td colspan="4">$bbCodes_text</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" cols="80" rows="25">$text</textarea></td></tr>
            <tr class="light">
                <td colspan="4" class="center">__Allow comments__: <input type="checkbox" name="opened" value="1" <!-- IF !empty($opened) -->checked<!-- ENDIF --> /></td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="section" value="$section_id" />
            <input type="hidden" name="category" value="$category_id"/>
            <input type="hidden" name="item" value="$item" />
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
