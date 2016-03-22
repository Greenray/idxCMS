<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: The template for editing or posting new topic

die();?>

<script type="text/javascript">
    function checkTopicForm(form) {
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
            ShowAlert('__Enter the text__');
            return false;
        }
        return true;
    }
</script>
<div>
    <form id="topic" name="topic" method="post"  onsubmit="return checkTopicForm(this);">
        <fieldset>
            <legend>__Topic__</legend>
            __Title__ <input type="text" name="title" value="$title" id="title" size="50" />
            $bbCodes
            <p><textarea id="text" name="text" cols="70" rows="10">$text</textarea></p>
            <!-- IF !empty($moderator) -->
                <div>
                    <input type="checkbox" name="opened" value="1" /><label for="opened">__Close__</label>
                    <input type="checkbox" name="pinned" value="1" /><label for="pinned">__Pin__</label>
                </div>
            <!-- ELSE -->
                <input type="hidden" name="opened" value="1" />
            <!-- ENDIF -->
        </fieldset>
        <p class="center">
            <!-- IF !empty($new) -->
                <input type="hidden" name="new" value="1" />
            <!-- ENDIF -->
            <input type="submit" name="save" value="__Save__" />
        </p>
  </form>
</div>
