<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module POSTS: Post template

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
            ShowAlert('__Enter a title__');
            return false;
        }
        if (title.match(textRegex)) {
            ShowAlert('__Invalid symbols__');
            return false;
        }
        if (text === '') {
            ShowAlert('__Enter a text__');
            return false;
        }
        return true;
    }
</script>
<!-- IF empty($admin) -->
    <div class="center">__Your article will be published after premoderation__</div>
<!-- ENDIF -->
<form id="editor" name="editor" method="post" action="" onsubmit="return checkPost(this);">
    <h2 class="center">$header</h2>
    <table id="std">
        <tr>
            <th>__Section__</th>
            <!-- IF !empty($admin) -->
                <td class="light"><input type="hidden" name="section" value="$section_id" /><strong>$section_title</strong></td>
                <th>__Select section__</th>
                <td class="light">
                    <select name="new_section" onChange="setCategories(this.selectedIndex)">
                    <!-- FOREACH section = $sections -->
                        <option value="$section.id" <!-- IF !empty($section.selected) -->selected<!-- ENDIF -->>$section.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            <!-- ELSE -->
                <td colspan="3" class="light"><strong>$section_title</strong></td>
            <!-- ENDIF -->
        </tr>
        <tr>
            <th>__Category__</td>
            <!-- IF !empty($admin) -->
                <td class="light"><input type="hidden" name="category" value="$category_id"/><strong>$category_title</strong></td>
                <th>__Select category__</th>
                <td class="light">
                    <select name="new_category">
                    <!-- FOREACH category = $categories -->
                        <option value="$category.id" <!-- IF !empty($category.selected) -->selected<!-- ENDIF -->>$category.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            <!-- ELSE -->
                <td colspan="3" class="light"><strong>$category_title</strong></td>
            <!-- ENDIF -->
        </tr>
        <tr>
            <th>__Title__</th>
            <td colspan="3" class="light"><input type="text" name="title" value="$title" id="title" size="60" class="required" /></td>
        </tr>
        <tr>
            <th>__Keywords__</th>
            <td colspan="3" class="light"><input type="text" id="keywords" name="keywords"  size="60"  value="$keywords" /></td>
        </tr>
        <tr>
            <td colspan="4" class="light">
                <div class="center">
                    <p>
                        <a href="#editor" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                            __Description__
                        </a>
                    </p>
                </div>
                <div id="shdesc" style="display:none;">
                    $bbCodes_desc
                    <div class="center"><textarea id="desc" name="desc" cols="80" rows="5" >$desc</textarea></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="light">
                <div class="center">__Text__</div>
                $bbCodes_text
                <div class="center"><textarea id="text" name="text" cols="80" rows="25">$text</textarea></div>
            </td>
        </tr>
        <tr>
        <!-- IF !empty($admin) -->
            <td colspan="4" class="light center">__Allow comments__: <input type="checkbox" name="opened" value="1" id="opened" <!-- IF !empty($opened) -->checked<!-- ENDIF --> /></td>
        <!-- ELSE -->
            <td colspan="4"><input type="hidden" name="opened" value="1" /></td>
        <!-- ENDIF -->
        </tr>
    </table>
    <p class="navigation center">
        <input type="hidden" name="item" value="$item" />
        <input type="reset" value="__Reset__" />
        <input type="submit" name="save" value="__Save__" />
    </p>
</form>
