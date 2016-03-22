<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Registration form

die();?>

<script type="text/javascript" src='{TOOLS}password.js'></script>
<script type="text/javascript">
    function checkUsername() {
        if (/^[a-zA-Z0-9]{3,10}$/.test(document.registration.user.value)) {
            document.getElementById('help').style.display = 'none';
            document.getElementById('good').style.display = 'block';
            document.getElementById('bad').style.display  = 'none';
        } else {
            document.getElementById('help').style.display = 'none';
            document.getElementById('good').style.display = 'none';
            document.getElementById('bad').style.display  = 'block';
        }
    }
    function checkUserForm(form) {
        var user       = form.user.value;
        var nick       = form.nick.value;
        var email      = form.email.value;
        var userRegex  = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        var nickRegex  = /^(([ \_][a-zA-Z0-9а-яА-ЯёЁ])?[a-zA-Z0-9а-яА-Я_ёЁ]*)*$/;
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if ((user === '') || !user.match(userRegex)) {
            ShowAlert('__Invalid login__');
            return false;
        }
        if ((nick === '') || !nick.match(nickRegex)) {
            ShowAlert('__Invalid nick__');
            return false;
        }
        if (form.password.value === '') {
            ShowAlert('__Enter your password__');
            return false;
        }
        if (form.confirm.value  === '') {
            ShowAlert('__Confirm password__');
            return false;
        }
        if (form.password.value !== form.confirm.value) {
            ShowAlert('__Error in password__');
            return false;
        }
        if ((email === '') || !email.match(emailRegex)) {
            ShowAlert('__Invalid email__');
            return false;
        }
        <!-- IF !empty($captcha) -->
            if  (form.captcheckout.value === '') {
                ShowAlert('__Enter a code__');
                return false;
            }
        <!-- ENDIF -->
        return true;
    }
</script>
<a href="#" id="register"></a>
<div id="registration_content">
    <div id="registration_form">
        <div id="steps">
            <form name="registration" id="registration" method="post"  enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
                <fieldset class="step">
                    <legend>__Account__</legend>
                    <p>
                        <label for="user">__Username__</label>
                        <input id="user" type="text" name="user" value="$user" class="required" onkeyup="checkUsername();" />
                        <span>
                            <span id="help" class="help block">__Only latin characters, digits and symbol "_"__</span>
                            <span id="good" class="none">__Login is allowed__</span>
                            <span id="bad" class="none">__Login is not allowed__</span>
                        </span>
                    </p>
                    <p>
                        <label for="nick">__Nick__</label>
                        <input type="text" name="nick" id="nick" value="$nick" class="required" required>
                    </p>
                    <p>
                        <label for="password">__Password__</label>
                        <input id="password" name="password" type="password" value="$password" class="required" autocomplete=off onkeyup="checkPassword('registration'); updatePasswordStrength(this, 'pass_rating', {'image':0, 'text':1});" />
                        <span id="pass_rating">
                            <span id="progresbar-2-0" class="pass_img"></span>
                            <span class="pass_title">__Password complexity__: </span>
                        </span>
                    </p>
                    <p>
                        <label for="confirm">__Confirm password__</label>
                        <input id="confirm" type="password" name="confirm" value="$confirm" class="required" autocomplete=off onkeyup="checkPassword('registration');" />
                        <span>
                            <span id="yes" class="help none">__Passwords are equal__</span>
                            <span id="no" class="help block">__Passwords are not equal__</span>
                        </span>
                    </p>
                    <p class="msg help">__Required fields have a yellow background__</p>
                </fieldset>
                <fieldset class="step">
                    <legend>__Additionally__</legend>
                    <p>
                        <label for="country">__Country__</label>
                        <input id="country" name="fields[country]" type="text" value="$country" />
                    </p>
                    <p>
                        <label for="city">__City__</label>
                        <input id="city" name="fields[city]" type="text" value="$city" />
                    </p>
                    <p>
                        <label for="tz">__Time zone__</label>
                        $utz
                    </p>
                </fieldset>
                <fieldset class="step">
                    <legend>__Communication__</legend>
                    <p>
                        <label for="email">__Email__</label>
                        <input id="email" name="email" placeholder="myemail@mydomain.ru" type="email" value="$email" class="required" />
                    </p>
                    <p>
                        <label for="website">__Website__</label>
                        <input id="website" name="fields[website]" placeholder="http://www.mywebsite.com" value="$website" type="url" />
                    </p>
                    <p class="msg help">__Required fields have a yellow background__</p>
                </fieldset>
                <fieldset class="step">
                    <legend>__Avatar__</legend>
                    <p><img src="$avatar" alt="AVATAR" /></p>
                    <p>
                        <label for="avatar">__Upload__</label>
                        <input id="avatar" type="file" name="avatar" />
                    </p>
                </fieldset>
                <fieldset class="step">
                    <legend>__Registration__</legend>
                    <p class="center">$captcha</p>
                    <p class="msg help">__Required fields have a yellow background__</p>
                    <p><input type="submit" name="save" value="__Save__" /></p>
                </fieldset>
            </form>
        </div>
    </div>
</div>
