<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Comment post template

die();?>

<script type="text/javascript" src='{TOOLS}limit.js'></script>
<script type="text/javascript">
    function checkForm(form) {
        if (form.text.value === '') {
            ShowAlert('__Enter the text__');
            return false;
        }
        <!-- IF !empty($captcha) -->
            if (form.captcheckout.value === '') {
                ShowAlert('__Enter a code__');
                return false;
            }
        <!-- ENDIF -->
        return true;
    }
</script>
<div class="comment_post center">
    $bbcodes
    <form id="comment" name="comment" method="post" action="$action" onsubmit="return checkForm(this);">
        <textarea id="text" name="text" cols="20" rows="7">$text</textarea>
        <!-- IF !empty($email) -->
            <p>
                <label>__Email__</label>
                <input type="email" id="email" name="email" size="30" value="$email" class="required" onClick="$(this).val('');" />
            </p>
        <!-- ENDIF -->
        <!-- IF !empty($message_length) -->
            <div>__Max message length__ [<script type="text/javascript">displayLimit("document.comment.text", "", '$message_length')</script>] __symbols__</div>
        <!-- ENDIF -->
        <!-- IF !empty($captcha) -->
            <p class="center">$captcha</p>
        <!-- ENDIF -->
        <p class="navigation center">
        <!-- IF !empty($for) -->
            <input type="hidden" name="for" value="$for" />
        <!-- ENDIF -->
            <input type="reset" name="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</div>
