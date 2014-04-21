<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# FORM FOR COMMENT EDITING

die();?>

<a name="{comment}"></a>
<form name="edit" method="post" action="">
    <input type="hidden" name="comment" value="{comment}" />
    {bbcodes}
    <fieldset>
        <legend>[__Text]</legend>
        <textarea id="text" name="text" cols="70" rows="10" style="width: 96%">{text}</textarea>
        [if=moderator]
            <div class="prevention"><label><input type="checkbox" name="block" value="1" /> [__Block]</label></div>
        [endif]
    </fieldset>
    <p class="center"><input type="submit" name="save" value="[__Save]" /></p>
</form>
