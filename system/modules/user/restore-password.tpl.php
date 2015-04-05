<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script type="text/javascript">
    function checkForm(form) {
        var name = form.name.value;
        if (name === '') {
            ShowAlert('[__Enter your name]', '[__Error]');
            return false;
        }
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
        [if=captcha]
            var capt = form.captcheckout.value;
            if (capt === '') {
                ShowAlert('[__Enter a code]', '[__Error]');
                return false;
            }
        [endif]
        return true;
    }
</script>
<form id="form" name="form" method="post" action="" onsubmit="return checkForm(this);">
    <table cellpadding="2" cellspacing="1" style="width:100%;">
        <tr>
            <td class="right">[__Username]</td>
            <td class="left"><input type="text" id="name" name="name" /></td>
        </tr>
        <tr>
            <td class="right">[__E-mail]</td>
            <td class="left"><input type="text" id="email" name="email" style="width:50%" /></td>
        </tr>
        <tr>
            <td class="center" colspan="2">
                <p>
                    {captcha}
                    <input type="submit" name="save" value="[__Submit]" class="submit" />
                </p>
            </td>
        </tr>
    </table>
</form>
