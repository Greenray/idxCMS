<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE USER - RESTORE PASSWORD TEMPLATE

die();?>
<script type="text/javascript">
    function checkForm(form) {
        var name = form.name.value;
        if (name == "") {
            inlineMsg('name', '[__Fill this field]');
            return false;
        }
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        var email = form.email.value;
        if (email == "") {
            inlineMsg('email', '[__Enter your email]');
            return false;
        }
        if (!email.match(emailRegex)) {
            inlineMsg('email', '[__You have entered an invalid email]');
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
