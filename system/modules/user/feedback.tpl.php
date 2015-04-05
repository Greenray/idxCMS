<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script type="text/javascript">
    function checkForm(form) {
        [if=email]
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            var email = form.email.value;
            if (email === '') {
                ShowAlert('[__Enter your email]', '[__Error]');
                return false;
            }
            if (!email.match(emailRegex)) {
                ShowAlert('[__Invalid email]', '[__Error]');
                return false;
            }
        [/if]
        var text = form.message.value;
        if (text === '') {
            ShowAlert('[__Enter a text]', '[__Error]');
            return false;
        }
        [if=captcha]
            var capt = form.captcheckout.value;
            if (capt === '') {
                ShowAlert('[__Enter a code]', '[__Error]');
                return false;
            }
        [/if]
        return true;
    }
</script>
<div class="center">[__Private message to Administrator]
<form id="form" name="form" method="post" action="" onsubmit="return checkForm(this);">
    [if=email]<input type="text" id="email" name="email" size="30" value="{email}" class="required" />[/if]
    <fieldset>
        <legend>[__Text]</legend>
        <textarea id="message" name="message" cols="120" rows="7">{message}</textarea>
    </fieldset>
    <p class="center">
        [if=captcha]{captcha}[/if]
        <input type="submit" value="[__Submit]" />
    </p>
</form>
</div>
