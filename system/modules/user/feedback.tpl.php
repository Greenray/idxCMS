<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FEEDBACK - TEMPLATE

die();?>
<script type="text/javascript">
    function checkForm(form) {
        [if=email]
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            var email = form.email.value;
            if (email == "") {
                inlineMsg('email', '[__Enter your email]');
                return false;
            }
            if (!email.match(emailRegex)) {
                inlineMsg('email', '[__Error]');
                return false;
            }
        [endif]
        var text = form.message.value;
        if (text == "") {
            inlineMsg('message', '[__Fill this field]');
            return false;
        }
        [if=captcha]
            var capt = form.captcheckout.value;
            if (capt == "") {
                inlineMsg('captcheckout', '[__Fill this field]');
                return false;
            }
        [endif]
        return true;
    }
</script>
<div class="center">[__Private message to Administrator]
<form id="form" name="form" method="post" action="" onsubmit="return checkForm(this);">
    [if=email][__E-mail] <input type="text" id="email" name="email" size="30" value="{email}" class="required" />[endif]
    <fieldset>
        <legend>[__Text]</legend>
        <textarea id="message" id="message" name="message" cols="70" rows="7">{message}</textarea>
    </fieldset>
    <p class="center">
        [if=captcha]{captcha}[endif]
        <input type="submit" value="[__Submit]" />
    </p>
</form>
</div>
