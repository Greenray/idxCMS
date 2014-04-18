<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE USER - PROFILE TEMPLATE

die();?>
<script type="text/javascript">
    function check(id) {
        var form = document.getElementById(id);
        if (form.password.value == form.confirm.value) {
            if (form.password.value !== "") {
                document.getElementById("yes").style.display = "";
                document.getElementById("no").style.display  = "none";
            }
        } else {
            document.getElementById("yes").style.display = "none";
            document.getElementById("no").style.display  = "";
        }
    }
    function getPasswordStrength(pw) {
        var pwlength = (pw.length);
        if (pwlength > 5) pwlength = 5;
        var numnumeric = pw.replace(/[0-9]/g, "");
        var numeric = (pw.length - numnumeric.length);
        if (numeric > 3) numeric = 3;
        var symbols = pw.replace(/\W/g, "");
        var numsymbols = (pw.length - symbols.length);
        if (numsymbols > 3) numsymbols = 3;
        var numupper = pw.replace(/[A-Z]/g, "");
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
        if (!pwdivEl) {
            alert('Password Strength Display Element Missing');
        }
        var divlist = pwdivEl.getElementsByTagName('div');
        var imgdivnum = 0;
        if (divorderlist && divorderlist.image > -1) {
            imgdivnum = divorderlist.image;
        }
        var imgdiv = divlist[imgdivnum];
        imgdiv.id  = 'passbar-' + bars;
    }
</script>
[ifelse=profile]
    <script type="text/javascript">
        function checkUserForm(form) {
            var capt = form.captcheckout.value;
            if (capt == "") {
                inlineMsg('captcheckout', '[__Enter a code]');
                return false;
            }
            return true;
        }
    </script>
    <form name="profile" id="profile" method="post" action="" enctype="multipart/form-data">
[else]
    <script type="text/javascript">
        function checkUsername(id) {
            if (/^[a-zA-Z0-9]{3,10}$/.test(document.registration.user.value)) {
                document.getElementById("help").style.display = "none";
                document.getElementById("good").style.display = "";
                document.getElementById("bad").style.display  = "none";
            } else {
                document.getElementById("help").style.display = "none";
                document.getElementById("good").style.display = "none";
                document.getElementById("bad").style.display  = "";
            }
        }
        function checkUserForm(form) {
            var user = form.user.value;
            var nick = form.nick.value;
            var password = form.password.value;
            var confirm  = form.confirm.value;
            var email = form.email.value;
            var capt = form.captcheckout.value;
            var userRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
            var nickRegex = /^[a-zA-Z0-9а-яА-Я_]+(([\_][a-zA-Z0-9а-яА-Я])?[a-zA-Z0-9_а-яА-Я]*)*$/;
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if (user == "") {
                inlineMsg('user', '[__Enter your name]');
                return false;
            }
            if (!user.match(userRegex)) {
                inlineMsg('user', '[__You have used an invalid symbols]');
                return false;
            }
            if (nick == "") {
                inlineMsg('nick', '[__Enter your name]');
                return false;
            }
            if (!nick.match(nickRegex)) {
                inlineMsg('nick', '[__You have used an invalid symbols]');
                return false;
            }
            if (password == "") {
                inlineMsg('password', '[__Enter password]');
                return false;
            }
            if (confirm == "") {
                inlineMsg('confirm', '[__Confirm password]');
                return false;
            }
            if (email == "") {
                inlineMsg('email', '[__Enter your email]');
                return false;
            }
            if (!email.match(emailRegex)) {
                inlineMsg('email', '[__You have entered an invalid email]');
                return false;
            }
            if (capt == "") {
                inlineMsg('captcheckout', '[__Enter a code]');
                return false;
            }
            return true;
        }
    </script>
    <form name="registration" id="registration" method="post" action="" enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
[endelse]
<input type="hidden" name="{mode}" value="1" />
<table class="std right">
    <tr class="even">
        <td colspan="3" class="center">
            <img src="{avatar}" hspace="5" vspace="5" alt="" />
            <p>
                <a href="#avatar" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                    [__Avatar]
                </a>
            </p>
            <div id="shdesc" style="display: none;"><div class="avatar"><input type="file" name="avatar" value="" /></div></div>
        </td>
    </tr>
    <tr class="even">
        <th style="width:165px;">[__Username]</th>
        [ifelse=profile]
            <td class="left" colspan="2"><input type="text" name="user" id="user" value="{username}" /></td>
       [else]
            <td class="left"><input type="text" name="user" id="user" onkeyup="checkUsername('{mode}');" value="{user}" class="required" /></td>
            <td class="center" style="width:380px;">
                <div style="display:block" id="help" class="help">[__Only latin characters, digits and symbol "_"]</div>
                <div style="display:none" id="good"><font color="#00cc00">[__Login is allowed]</font></div>
                <div style="display:none" id="bad"><font color="#ff0000">[__Login is not allowed]</font></div>
            </td>
        [endelse]
    </tr>
    <tr class="even">
        <th>[__Nick]</th>
       [ifelse=profile]
            <td class="left" colspan="2">{nickname}</td>
       [else]
            <td colspan="2" class="left"><input type="text" name="nick" id="nick" value="{nick}" class="required" /></td>
       [endelse]
    </tr>
    [if=profile]
        <tr class="even"><th>[__Access level]</th><td colspan="2" class="left">{access}</td></tr>
        <tr class="even"><th>[__Personal status]</th><td colspan="2" class="left">{status}</td></tr>
        <tr class="even"><th>[__Rate]</th><td colspan="2" class="left">{stars}</td></tr>
        <tr class="even">
            <th>[__Rights]</th>
            [ifelse=admin]
                <td colspan="2" class="left">[__You have all rights on this site]</td>
            [else]
                <td colspan="2" class="left">{rights}</td>
            [endelse]
        </tr>
        <tr class="even">
            <th>[__Current password]</th>
            <td class="left"><input type="password" name="current_password" /></td>
            <td class="center">[__To change data you must enter your current password]</td>
        </tr>
    [endif]
        <tr class="even">
            <th>[__Password]</th>
            <td class="left"><input type="password" name="password" onkeyup="check('{mode}'); updatePasswordStrength(this,'passwdRating',{ 'image':0, 'text':1 });" value="" class="required" /></td>
            <td class="center">
                <div id="passwdRating">
                    <span id="ps-title">[__Password complexity] </span>
                    <div id="progresbar-2-0" class="pass_img"></div>
                </div>
            </td>
        </tr>
        <tr class="even">
            <th>[__Confirm password]</th>
            <td class="left"><input type="password" name="confirm" onkeyup="check('{mode}');" value="" class="required"  /></td>
            <td class="center">
                <div style="display: none" id="yes"><font color="#33cc00">[__Passwords are equal]</font></div>
                <div style="display: none" id="no"><font color="#ff0000">[__Passwords are not equal]</font></div>
            </td>
        </tr>
        <tr class="even">
            <th>[__E-mail]</th>
            <td colspan="2" class="left"><input type="text" name="email" id="email" value="{email}" size="35" class="required" /></td>
        </tr>
        <tr class="even"><th>[__Time zone]</th><td colspan="2" class="left">{utz}</td></tr>
        <tr class="even"><th>[__ICQ]</th><td colspan="2" class="left"><input type="text" name="fields[icq]" value="{icq}" size="12" /></td></tr>
        <tr class="even"><th>[__Website]</th><td colspan="2" class="left"><input type="text" name="fields[website]" value="{website}" size="50" /></td></tr>
        <tr class="even"><th>[__Country]</th><td colspan="2" class="left"><input type="text" name="fields[country]" value="{country}" size="25" /></td></tr>
        <tr class="even"><th>[__City]</th><td colspan="2" class="left"><input type="text" name="fields[city]" value="{city}" size="25" /></td></tr>
        [ifelse=profile]
            <tr class="even"><th>[__Registration]</th><td colspan="2" class="center">{regdate}</td></tr>
            <tr class="even"><th>[__Last visit]</th><td colspan="2" class="center">{lastvisit}</td></tr>
            <tr class="even"><th>[__Visits]</th><td colspan="2" class="center">{visits}</td></tr>
            <tr class="even"><th>[__Posts]</th><td colspan="2" class="center">{posts}</td></tr>
            <tr class="even"><th>[__Comments]</th><td colspan="2" class="center">{comments}</td></tr>
            <tr class="even"><th>[__Topics]</th><td colspan="2" class="center">{topics}</td></tr>
            <tr class="even"><th>[__Replies]</th><td colspan="2" class="center">{replies}</td></tr>
        [else]
            <tr class="even"><td colspan="3" class="help">[__Required fields have a yellow background]</td></tr>
        [endelse]
    </table>
    <p class="center">
        [if=captcha]{captcha}[endif]
        <input type="submit" name="save" value="[__Save]" class="submit" />
    </p>
</form>
