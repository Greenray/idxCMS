<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Proile template

die();?>

<script type="text/javascript">
    function checkPassword(id) {
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
<form name="profile" id="profile" method="post"  enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
    <input type="hidden" name="profile" value="1" />
    <table class="profile right">
        <tr class="dark">
            <td colspan="3" class="center">
                <img src="$avatar" alt="AVATAR" />
                <p>
                    <a href="#" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                        __Avatar__
                    </a>
                </p>
                <div id="shdesc" style="display:none;">
                    <div class="avatar">
                        <label for="avatar">__Upload__</label>
                        <input id="avatar" type="file" name="avatar" />
                    </div>
                </div>
            </td>
        </tr>
        <tr class="light">
            <th style="width:165px;">__Username__</th><td colspan="2" class="left">$user</td></tr>
        <tr class="dark">
            <th>__Nick__</th>
            <td colspan="2" class="left">$nick</td>
        </tr>
        <tr class="light">
            <th>__Access level__</th>
            <td colspan="2" class="left">$access</td>
        </tr>
        <tr class="dark">
            <th>__Personal status__</th>
            <td colspan="2" class="left">$status</td>
        </tr>
        <tr class="light">
            <th>__Rate__</th>
            <td colspan="2" class="left">$stars</td>
        </tr>
        <tr class="dark">
            <th>__Rights__</th>
            <!-- IF !empty($admin) -->
                <td colspan="2" class="left">__You have all rights on this site__</td>
            <!-- ELSE -->
                <td colspan="2" class="left">$rights</td>
            <!-- ENDIF -->
        </tr>
        <tr class="light">
            <th>__Current password__</th>
            <td class="left"><input type="password" name="current_password" /></td>
            <td class="left help">__To change data you must enter your current password__</td>
        </tr>
        <tr class="dark">
            <th>__Password__</th>
            <td class="left"><input type="password" name="password" value="" class="required" autocomplete=off onkeyup="checkPassword('profile'); updatePasswordStrength(this,'passwdRating',{ 'image':0, 'text':1 });" /></td>
            <td class="left pl20"><span id="passwdRating">__Password complexity__: <span id="progresbar-2-0" class="pass_img"></span></span></td>
        </tr>
        <tr class="light">
            <th>__Confirm password__</th>
            <td class="left"><input type="password" name="confirm" onkeyup="checkPassword('profile');" value="" class="required"  /></td>
            <td class="left pl20">
                <div id="yes" class="none"><font color="#33cc00">__Passwords are equal__</font></div>
                <div id="no" class="none"><font color="#ff0000">__Passwords are not equal__</font></div>
            </td>
        </tr>
        <tr class="dark">
            <th>__E-mail__</th>
            <td colspan="2" class="left"><input type="email" name="email" value="$email" size="35" class="required" /></td>
        </tr>
        <tr class="light">
            <th>__Time zone__</th><td colspan="2" class="left">$utz</td>
        </tr>
        <tr class="dark">
            <th>__Website__</th>
            <td colspan="2" class="left"><input type="text" name="fields[website]" value="$website" size="50" /></td>
        </tr>
        <tr class="light">
            <th>__Country__</th>
            <td colspan="2" class="left"><input type="text" name="fields[country]" value="$country" size="25" /></td>
        </tr>
        <tr class="dark">
            <th>__City__</th>
            <td colspan="2" class="left"><input type="text" name="fields[city]" value="$city" size="25" /></td>
        </tr>
        <tr class="light">
            <th>__Registration__</th>
            <td colspan="2" class="center">$regdate</td>
        </tr>
        <tr class="dark">
            <th>__Last visit__</th>
            <td colspan="2" class="center">$lastvisit</td>
        </tr>
        <tr class="light">
            <th>__Visits__</th>
            <td colspan="2" class="center">$visits</td>
        </tr>
        <tr class="dark">
            <th>__Posts__</th>
            <td colspan="2" class="center">$posts</td>
        </tr>
        <tr class="light">
            <th>__Comments__</th>
            <td colspan="2" class="center">$comments</td>
        </tr>
        <tr class="dark">
            <th>__Topics__</th>
            <td colspan="2" class="center">$topics</td>
        </tr>
        <tr class="light">
            <th>__Replies__</th>
            <td colspan="2" class="center">$replies</td>
        </tr>
        <tr class="dark">
            <td colspan="3"><p class="msg help">__Required fields have a yellow background__</p></td>
        </tr>
    </table>
    <p class="center">
        <!-- IF !empty($captcha) -->$captcha<!-- ENDIF -->
        <input type="submit" name="save" value="__Save__" />
    </p>
</form>
