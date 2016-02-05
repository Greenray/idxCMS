<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Form for comment editing

die();?>

<a id="$comment"></a>
<div class="comment_edit center">
    $bbcodes
    <form name="edit" method="post" action="">
        <textarea id="text" name="text" cols="70" rows="10">$text</textarea>
        <!-- IF !empty($moderator) -->
            <div class="center"><input type="checkbox" name="block" value="1" /> __Block user__</div>
        <!-- ENDIF -->
        <p class="center">
            <input type="hidden" name="comment" value="$comment" />
            <input type="reset" name="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</div>
