<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - NEW TOPIC TEMPLATE

die();?>
<script type="text/javascript">
    function checkTopicForm(form) {
        var title = form.title.value;
        var text = form.text.value;
        var textRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
        if (title == "") {
            inlineMsg('title', '[__Fill this field]');
            return false;
        }
        if (title.match(textRegex)) {
            inlineMsg('title', '[__You have used an invalid symbols]');
            return false;
        }
        if (text == "") {
            inlineMsg('text', '[__Fill this field]');
            return false;
        }
        return true;
    }
</script>
<div>
    <form id="topic" name="topic" method="post" action="" onsubmit="return checkTopicForm(this);">
        <fieldset>
            <legend>[__Topic]</legend>
            [__Title] <input type="text" name="title" value="{title}" id="title" size="50" />
            {bbCodes}
            <p><textarea id="text" name="text" cols="70" rows="10">{text}</textarea></p>
            [ifelse=moderator]
                <div>
                    <input type="checkbox" name="opened" value="1" />
                    <label for="opened">[__Close]</label>
                    <input type="checkbox" name="pinned" value="1" /><label for="pinned">[__Pin]</label>
                </div>
            [else]
                <input type="hidden" name="opened" value="1" />
            [endelse]
        </fieldset>
        <p class="center">
            [if=new]<input type="hidden" name="new" value="1" />[endif]
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
  </form>
</div>
