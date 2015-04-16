<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
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
    function updatePasswordStrength(pwbox, pwdiv, divorderlist) {
        var bpb  = "" + pwbox.value;
        var pwstrength = getPasswordStrength(bpb);
        var bars = (parseInt(pwstrength / 10) * 10);
        var pwdivEl = document.getElementById(pwdiv);
        if (!pwdivEl) alert('Password strength display element missing');
        var divlist = pwdivEl.getElementsByTagName('span');
        var imgdivnum = 0;
        if (divorderlist && divorderlist.image > -1) imgdivnum = divorderlist.image;
        var imgdiv = divlist[imgdivnum];
        imgdiv.id  = 'passbar-' + bars;
    }
</script>
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
        var nickRegex  = /^[a-zA-Z0-9а-яА-Я_]+(([\_][a-zA-Z0-9а-яА-Я])?[a-zA-Z0-9а-яА-Я_]*)*$/;
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if ((user === '') || !user.match(userRegex)) {
            ShowAlert('[__Invalid login]', '[__Error]');
            return false;
        }
        if ((nick === '') || !nick.match(nickRegex)) {
            ShowAlert('[__Invalid nickname]', '[__Error]');
            return false;
        }
        if (form.password.value === '') {
            ShowAlert('[__Enter your password]', '[__Error]');
            return false;
        }
        if (form.confirm.value  === '') {
            ShowAlert('[__Confirm password]', '[__Error]');
            return false;
        }
        if (form.password.value !== form.confirm.value) {
            ShowAlert('[__Error in password]', '[__Error]');
            return false;
        }
        if ((email === '') || !email.match(emailRegex)) {
            ShowAlert('[__Invalid email]', '[__Error]');
            return false;
        }
        [if=captcha]
            if  (form.captcheckout.value === '') {
                ShowAlert('[__Enter a code]', '[__Error]');
                return false;
            }
        [/if]
        return true;
    }
</script>
<form name="profile" id="profile" method="post" action="" enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
    <input type="hidden" name="profile" value="1" />
    <table class="std right">
        <tr class="even"><td colspan="3">&nbsp;</td></tr>
        <tr class="even">
            <td colspan="3" class="center">
                <img src="{avatar}" hspace="5" vspace="5" alt="" />
                <p><a href="#avatar" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">[__Avatar]</a></p>
                <div id="shdesc" class="none"><div class="avatar"><input type="file" name="avatar" value="" class="submit" /></div></div>
            </td>
        </tr>
        <tr class="even"><th style="width:165px;">[__Username]</th><td class="left" colspan="2">{username}</td></tr>
        <tr class="even"><th>[__Nick]</th><td class="left" colspan="2">{nickname}</td></tr>
        <tr class="even"><th>[__Access level]</th><td colspan="2" class="left">{access}</td></tr>
        <tr class="even"><th>[__Personal status]</th><td colspan="2" class="left">{status}</td></tr>
        <tr class="even"><th>[__Rate]</th><td colspan="2" class="left">{stars}</td></tr>
        <tr class="even">
            <th>[__Rights]</th>
            [ifelse=admin]
                <td colspan="2" class="left">[__You have all rights on this site]</td>
            [else]
                <td colspan="2" class="left">{rights}</td>
            [/else]
        </tr>
        <tr class="even">
            <th>[__Current password]</th>
            <td class="left"><input type="password" name="current_password" /></td>
            <td class="left pl20">[__To change data you must enter your current password]</td>
        </tr>
        <tr class="even">
            <th>[__Password]</th>
            <td class="left">
                <input type="password" name="password" value="" class="required" AUTOCOMPLETE=OFF onkeyup="check('profile'); updatePasswordStrength(this,'passwdRating',{ 'image':0, 'text':1 });" />
            </td>
            <td class="left pl20"><span id="passwdRating">[__Password complexity]: <span id="progresbar-2-0" class="pass_img"></span></span></td>
        </tr>
        <tr class="even">
            <th>[__Confirm password]</th>
            <td class="left"><input type="password" name="confirm" onkeyup="check('profile');" value="" class="required"  /></td>
            <td class="left pl20">
                <div id="yes" class="none"><font color="#33cc00">[__Passwords are equal]</font></div>
                <div id="no" class="none"><font color="#ff0000">[__Passwords are not equal]</font></div>
            </td>
        </tr>
        <tr class="even"><th>[__E-mail]</th><td colspan="2" class="left"><input type="text" name="email" id="email" value="{email}" size="35" class="required" /></td></tr>
        <tr class="even"><th>[__Time zone]</th><td colspan="2" class="left">{utz}</td></tr>
        <tr class="even"><th>[__ICQ]</th><td colspan="2" class="left"><input type="text" name="fields[icq]" value="{icq}" size="12" /></td></tr>
        <tr class="even"><th>[__Website]</th><td colspan="2" class="left"><input type="text" name="fields[website]" value="{website}" size="50" /></td></tr>
        <tr class="even"><th>[__Country]</th><td colspan="2" class="left"><input type="text" name="fields[country]" value="{country}" size="25" /></td></tr>
        <tr class="even"><th>[__City]</th><td colspan="2" class="left"><input type="text" name="fields[city]" value="{city}" size="25" /></td></tr>
        <tr class="even"><th>[__Registration]</th><td colspan="2" class="center">{regdate}</td></tr>
        <tr class="even"><th>[__Last visit]</th><td colspan="2" class="center">{lastvisit}</td></tr>
        <tr class="even"><th>[__Visits]</th><td colspan="2" class="center">{visits}</td></tr>
        <tr class="even"><th>[__Posts]</th><td colspan="2" class="center">{posts}</td></tr>
        <tr class="even"><th>[__Comments]</th><td colspan="2" class="center">{comments}</td></tr>
        <tr class="even"><th>[__Topics]</th><td colspan="2" class="center">{topics}</td></tr>
        <tr class="even"><th>[__Replies]</th><td colspan="2" class="center">{replies}</td></tr>
        <tr class="even"><td colspan="3">&nbsp;</td></tr>
    </table>
    <p class="center">
        [if=captcha]{captcha}[/if]
        <input type="submit" name="save" value="[__Save]" class="submit" />
    </p>
</form>
