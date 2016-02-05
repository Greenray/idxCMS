<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Feedback template

die();?>

<script type="text/javascript" src='{TOOLS}limit.js'></script>
<script type="text/javascript">
    function checkForm(form) {
        <!-- IF !empty($email) -->
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if ((form.email.value === '') || !form.email.match(emailRegex)) {
                ShowAlert('__Error in the email__');
                return false;
            }
        <!-- ENDIF -->
        if (form.message.value === '') {
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
    <div><h2>__Private message to Administrator__</h2></div>
    $bbcodes
    <form id="feedback" name="feedback" method="post" action="$action" onsubmit="return checkForm(this);">
        <!-- IF !empty($email) -->
            <p>
                <label>__Email__</label>
                <input type="email" id="email" name="email" size="30" value="$email" class="required" />
            </p>
        <!-- ENDIF -->
        <textarea id="text" name="text" cols="20" rows="7">$message</textarea>
        <!-- IF !empty($message_length) -->
        <div>__Max message length__ [<script type="text/javascript">displayLimit("document.feedback.text", "", '$message_length')</script>] __symbols__</div>
        <!-- ENDIF -->
        <!-- IF !empty($captcha) -->
            <p class="center">$captcha</p>
        <!-- ENDIF -->
        <p class="center">
        <!-- IF !empty($captcha) -->$captcha<!-- ENDIF -->
            <button type="submit">__Submit__</button>
            <button type="reset">__Reset__</button>
        </p>
    </form>
</div>
