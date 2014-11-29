<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE USER - PROFILE TEMPLATE

die();?>

<link rel="stylesheet" href="{TOOLS}sliding.css" type="text/css" media="screen"/>
<script type="text/javascript" src="{TOOLS}sliding.form.js"></script>
<script type="text/javascript">
    function check(id) {
        var form = document.getElementById(id);
        if (form.password.value === form.confirm.value) {
            if (form.password.value !== '') {
                document.getElementById('yes').style.display = 'block';
                document.getElementById('no').style.display  = 'none';
            }
        } else {
            document.getElementById('yes').style.display = 'none';
            document.getElementById('no').style.display  = 'block';
        }
    }
    function getPasswordStrength(pw) {
        var pwlength = (pw.length);
        if (pwlength > 5) pwlength = 5;
        var numnumeric = pw.replace(/[0-9]/g, '');
        var numeric = (pw.length - numnumeric.length);
        if (numeric > 3) numeric = 3;
        var symbols = pw.replace(/\W/g, '');
        var numsymbols = (pw.length - symbols.length);
        if (numsymbols > 3) numsymbols = 3;
        var numupper = pw.replace(/[A-Z]/g, '');
        var upper = (pw.length - numupper.length);
        if (upper > 3) upper = 3;
        var pwstrength = ((pwlength * 10) - 20) + (numeric * 10) + (numsymbols * 15) + (upper * 10);
        if (pwstrength < 0)   pwstrength = 0;
        if (pwstrength > 100) pwstrength = 100;
        return pwstrength;
    }
    function updatePasswordStrength(pwbox,pwdiv,divorderlist) {
        var bpb = "" + pwbox.value;
        var pwstrength = getPasswordStrength(bpb);
        var bars = (parseInt(pwstrength / 10) * 10);
        var pwdivEl = document.getElementById(pwdiv);
        if (!pwdivEl) alert('Password Strength Display Element Missing');
        var divlist = pwdivEl.getElementsByTagName('div');
        var imgdivnum = 0;
        if (divorderlist && divorderlist.image > -1) imgdivnum = divorderlist.image;
        var imgdiv = divlist[imgdivnum];
        imgdiv.id  = 'passbar-' + bars;
    }
</script>
<script type="text/javascript">
    function checkUsername(id) {
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
        var user = form.user.value;
        var nick = form.nick.value;
        var password = form.password.value;
        var confirm  = form.confirm.value;
        var email = form.email.value;
        var captcha = form.captcheckout.value;
        var userRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        var nickRegex = /^[a-zA-Z0-9а-яА-Я_]+(([\_][a-zA-Z0-9а-яА-Я])?[a-zA-Z0-9а-яА-Я_]*)*$/;
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if  (user === '') {
            ShowAlert('[__Enter your login]', '[__Error]');
            return false;
        }
        if (!user.match(userRegex)) {
            ShowAlert('[__Invalid symbols]', '[__Error]');
            return false;
        }
        if  (nick === '') {
            ShowAlert('[__Enter your name]', '[__Error]');
            return false;
        }
        if (!nick.match(nickRegex)) {
            ShowAlert('[__Invalid symbols]', '[__Error]');
            return false;
        }
        if  (password === '') {
            ShowAlert('[__Enter your password]', '[__Error]');
            return false;
        }
        if  (confirm  === '') {
            ShowAlert('[__Confirm password]', '[__Error]');
            return false;
        }
        if  (email === '') {
            ShowAlert('[__Enter your email]', '[__Error]');
            return false;
        }
        if (!email.match(emailRegex)) {
            ShowAlert('[__Invalid email]', '[__Error]');
            return false;
        }
        if  (captcha === '') {
            ShowAlert('[__Enter a code]', '[__Error]');
            return false;
        }
    }
</script>
<div id="content">
    <div id="sl_form">
        <div id="steps">
            <form name="registration" id="registration" method="post" action="" enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
                <fieldset class="step" title="текст">
                    <legend>[__Account]</legend>
                    <p>
                        <label for="user">[__Username]</label>
                        <input type="text" name="user" id="user" value="{user}" class="required" />
                    </p>
                    <p>
                        <label for="nick">[__Nick]</label>
                        <input type="text" name="nick" id="nick" value="{nick}" class="required" />
                    </p>
                    <p>
                        <label for="password">[__Password]</label>
                        <input id="password" name="password" type="password" value="{password}" class="required" AUTOCOMPLETE=OFF />
                    </p>
                    <p>
                        <label for="confirmconfirm">[__Confirm password]</label>
                        <input id="confirm" type="password" name="confirm" value="{confirm}" class="required" AUTOCOMPLETE=OFF />
                    </p>
                    <p class="msg help">[__Required fields have a yellow background]</p>
                </fieldset>
                <fieldset class="step">
                    <legend>[__Additionally]</legend>
                    <p>
                        <label for="country">[__Country]</label>
                        <input id="country" name="fields[country]" type="text" value="{country}" />
                    </p>
                    <p>
                        <label for="city">[__City]</label>
                        <input id="city" name="fields[city]" type="text" value="{city}" />
                    </p>
                    <p>
                        <label for="tz">[__Time zone]</label>
                        {utz}
                    </p>
                </fieldset>
                <fieldset class="step">
                    <legend>[__Communication]</legend>
                    <p>
                        <label for="email">[__Email]</label>
                        <input id="email" name="email" placeholder="myemail@mydomain.ru" type="email" value="{email}" class="required" />
                    </p>
                    <p>
                        <label for="icq">[__ICQ]</label>
                        <input id="icq" name="fields[icq]" type="text" value="{icq}" />
                    </p>
                    <p>
                        <label for="website">[__Website]</label>
                        <input id="website" name="fields[website]" placeholder="e.g. http://www.mewebsite.com" value="{website}" type="text" />
                    </p>
                    <p class="msg help">[__Required fields have a yellow background]</p>
                </fieldset>
                <fieldset class="step">
                    <legend>[__Avatar]</legend>
                    <p><img src="{avatar}" hspace="5" vspace="5" alt="" /></p>
                    <p>
                        <label for="avatar">[__Choose / Upload]</label>
                        <input id="avatar" type="file" name="avatar" value="{avatar}" />
                    </p>
                </fieldset>
                <fieldset class="step">
                    <legend>[__Registration]</legend>
                    <p>{captcha}</p>
                    <p class="msg help">[__Required fields have a yellow background]</p>
                    <p class="submit"><button type="submit" name="save" value="1">[__Save]</button></p>
                </fieldset>
                <a class="close" href="{ROOT}"></a>
            </form>
        </div>
        <div id="navigation" style="display:none;">
            <ul>
                <li class="selected"><a href="#">[__Account]</a></li>
                <li><a href="#">[__Additionally]</a></li>
                <li><a href="#">[__Communication]</a></li>
                <li><a href="#">[__Avatar]</a></li>
                <li><a href="#">[__Save]</a></li>
            </ul>
        </div>
    </div>
</div>
