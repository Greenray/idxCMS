<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<script type="text/javascript">
    function checkUserForm(form) {
        var captcha = form.captcheckout.value;
        if (captcha === '') {
            ShowAlert('[__Enter a code]', '[__Error]');
            return false;
        }
        return true;
    }
</script>
<form name="profile" id="profile" method="post" action="" enctype="multipart/form-data" onsubmit="return checkUserForm(this);">
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
                <div id="shdesc" class="none">
                    <div class="avatar"><input type="file" name="avatar" value="" class="submit" /></div>
                </div>
            </td>
        </tr>
        <tr class="even">
            <th style="width:165px;">[__Username]</th>
            <td class="left" colspan="2">{username}</td>
        </tr>
        <tr class="even">
            <th>[__Nick]</th>
            <td class="left" colspan="2">{nickname}</td>
        </tr>
        <tr class="even">
            <th>[__Access level]</th>
            <td colspan="2" class="left">{access}</td>
        </tr>
        <tr class="even">
            <th>[__Personal status]</th>
            <td colspan="2" class="left">{status}</td>
        </tr>
        <tr class="even">
            <th>[__Rate]</th>
            <td colspan="2" class="left">{stars}</td>
        </tr>
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
                <div id="yes" class="none"><font color="#33cc00">[__Passwords are equal]</font></div>
                <div id="no" class="none"><font color="#ff0000">[__Passwords are not equal]</font></div>
            </td>
        </tr>
        <tr class="even">
            <th>[__E-mail]</th>
            <td colspan="2" class="left"><input type="text" name="email" id="email" value="{email}" size="35" class="required" /></td>
        </tr>
        <tr class="even">
            <th>[__Time zone]</th>
            <td colspan="2" class="left">{utz}</td>
        </tr>
        <tr class="even">
            <th>[__ICQ]</th>
            <td colspan="2" class="left"><input type="text" name="fields[icq]" value="{icq}" size="12" /></td>
        </tr>
        <tr class="even">
            <th>[__Website]</th>
            <td colspan="2" class="left"><input type="text" name="fields[website]" value="{website}" size="50" /></td>
        </tr>
        <tr class="even">
            <th>[__Country]</th>
            <td colspan="2" class="left"><input type="text" name="fields[country]" value="{country}" size="25" /></td>
        </tr>
        <tr class="even">
            <th>[__City]</th>
            <td colspan="2" class="left"><input type="text" name="fields[city]" value="{city}" size="25" /></td>
        </tr>
        <tr class="even">
            <th>[__Registration]</th>
            <td colspan="2" class="center">{regdate}</td>
        </tr>
        <tr class="even">
            <th>[__Last visit]</th>
            <td colspan="2" class="center">{lastvisit}</td>
        </tr>
        <tr class="even">
            <th>[__Visits]</th>
            <td colspan="2" class="center">{visits}</td>
        </tr>
        <tr class="even">
            <th>[__Posts]</th>
            <td colspan="2" class="center">{posts}</td>
        </tr>
        <tr class="even">
            <th>[__Comments]</th>
            <td colspan="2" class="center">{comments}</td>
        </tr>
        <tr class="even">
            <th>[__Topics]</th>
            <td colspan="2" class="center">{topics}</td>
        </tr>
        <tr class="even">
            <th>[__Replies]</th>
            <td colspan="2" class="center">{replies}</td>
        </tr>
    </table>
    <p class="center">
        [if=captcha]{captcha}[endif]
        <input type="submit" name="save" value="[__Save]" class="submit" />
    </p>
</form>
