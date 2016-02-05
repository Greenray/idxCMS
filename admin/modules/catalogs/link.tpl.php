<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Link template.

die();?>

<script type="text/javascript">
    function checkLink(form) {
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
            ShowAlert('__Enter a description__');
            return false;
        }
        return true;
    }
</script>
<div class="module">__Link__</div>
<fieldset>
    <form name="item" method="post" action="" enctype="multipart/form-data" onsubmit="return checkLink(this);">
        <table class="std">
            <tr class="light">
            <!-- IF !empty($category_id) -->
                <td>__Category__</td>
                <td><input type="hidden" name="category" value="$category_id"/><b>$category_title</b></td>
                <td>__Select category__</td>
                <td>
            <!-- ELSE -->
                <td>__Select category__</td>
                <td colspan="3">
            <!-- ENDIF -->
                    <select name="new_category">
                    <!-- FOREACH category = $categories -->
                        <option value="$category.id" <!-- IF !empty($category.selected) -->selected="selected"<!-- ENDIF -->>$category.title</option>
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
                            <a href="#item" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                                __Description__
                            </a>
                        </p>
                    </div>
                    <div id="shdesc" style="display:none;">
                        $bbCodes_desc
                        <textarea id="desc" name="desc" rows="5" >$desc</textarea>
                    </div>
                </td>
            </tr>
            <tr><th colspan="4">__Text__</th></tr>
            <tr><td colspan="4">$bbCodes_text</td></tr>
            <tr><td colspan="4"><textarea id="text" name="text" id="text" rows="15">$text</textarea></td></tr>
            <tr class="light">
                <td>__Link__</td>
                <td colspan="3"><input type="text" name="site" value="$site" size="50" /></td>
            </tr>
            <tr class="light">
                <td>__Copyright__</td>
                <td colspan="3">&copy; <input type="text" name="copyright" value="$copyright" size="50" /></td>
            </tr>
            <tr class="light">
                <td>__Allow comments__</td>
                <td colspan="3"><input type="checkbox" name="opened" value="1" <!-- IF !empty($opened) -->checked<!-- ENDIF --> /></td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="item" value="$id" />
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
