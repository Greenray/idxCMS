<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration:  Image template.

die();?>

<script type="text/javascript">
    function checkImage(form) {
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
<div class="module">__Image__</div>
<fieldset>
    <form name="item" method="post"  enctype="multipart/form-data" onsubmit="return checkImage(this);">
        <table class="std">
            <tr class="light">
                <td class="label">__Section__</td>
                <td colspan="3"><input type="hidden" name="section" value="$section_id" /><b>$section_title</b></td>
            </tr>
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
            <!-- IF !empty($image) -->
                <tr class="light">
                    <td>__Image__</td>
                    <td colspan="2" class="left">
                        <input type="hidden" name="item" value="$id" />
                        <b><img src="[$image:].jpg" alt="Image" /></b>
                    </td>
                    <td>$name</td>
                </tr>
            <!-- ENDIF -->
            <tr class="light">
                <td>__Upload__</td>
                <td colspan="3"><input type="file" name="image" /></td>
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
            <tr><td colspan="4"><textarea id="text" name="text" rows="15">$text</textarea></td></tr>
            <tr class="light">
                <td>__Copyright__</td>
                <td colspan="3">&copy; <input type="text" name="copyright" value="$copyright" size="50" /></td>
            </tr>
            <tr class="light">
                <td>__Comments__</td>
                <td colspan="3">
                    <input type="checkbox" name="opened" value="1" <!-- IF !empty($opened) -->checked<!-- ENDIF --> />
                    <label for="opened"> __Allow__</label>
                </td>
            </tr>
        </table>
        <p class="center">
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
