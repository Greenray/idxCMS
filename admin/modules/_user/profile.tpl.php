<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: User profile template.

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
</script>
<div class="module">__User profile__</div>
<fieldset>
    <form name="profile" id="profile" method="post" >
        <table class="std">
            <tr class="light">
                <td class="right">__Username__</td>
                <td colspan="2" class="left">
                    <input type="hidden" name="user" value="$user" />$user
                </td>
            </tr>
            <tr class="light">
                <td class="right">__Nick__</td>
                <td colspan="2" class="left">
                    <input type="text" name="nick" value="$nick" class="required" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__Avatar__</td>
                <td colspan="2" class="left">
                    <img src="$avatar" alt="AVATAR" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__E-mail__</td>
                <td  colspan="2">
                    <input type="text" name="email" value="$email" class="required" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__Time zone__</td>
                <td colspan="2" class="left">$utz</td>
            </tr>
            <tr class="light">
                <td class="right">__Personal status__</td>
                <td colspan="2">
                    <input type="text" name="status" value="$status" /></td>
            </tr>
            <tr class="light">
                <td class="right">__Rate__</td>
                <td colspan="2" class="left">$stars</td></tr>
            <tr class="light">
                <td class="right">__Access level__</td>
                <td colspan="2">
                    <input type="text" name="access" value="$access" size="2" class="required" />
                </td>
            </tr>
            <tr class="light"><td class="right">__Registration__</td><td colspan="2">$regdate</td></tr>
            <tr class="light"><td class="right">__Last visit__</td><td colspan="2">$lastvisit</td></tr>
            <tr class="light"><td class="right">__Visits__</td><td colspan="2">$visits</td></tr>
            <tr class="light"><td class="right">__Posts__</td><td colspan="2">$posts</td></tr>
            <tr class="light"><td class="right">__Comments__</td><td colspan="2">$comments</td></tr>
            <tr class="light"><td class="right">__Topics__</td><td colspan="2">$topics</td></tr>
            <tr class="light"><td class="right">__Replies__</td><td colspan="2">$replies</td></tr>
            <tr class="light">
                <td class="right">__Website__</td>
                <td colspan="2" class="left">
                    <input type="text" name="fields[website]" value="$website" size="50" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__Country__</td>
                <td colspan="2" class="left">
                    <input type="text" name="fields[country]" value="$country" size="25" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__City__</td>
                <td colspan="2" class="left">
                    <input type="text" name="fields[city]" value="$city" size="25" />
                </td>
            </tr>
            <tr class="light">
                <td class="right">__Blocked__</td>
                <td colspan="2" class="left">
                    <input type="checkbox" name="blocked" value="1" <!-- IF !empty($blocked) -->checked<!-- ENDIF --> />
                </td>
            </tr>
            <tr class="dark">
                <td>__Password__</td>
                <td colspan="2" class="left">
                    <input type="password" name="password" onkeyup="check('profile');" value="" />
                </td>
            </tr>
            <tr class="dark">
                <td>__Confirm password__</td>
                <td class="left">
                    <input type="password" name="confirm" onkeyup="check('profile');" value="" />
                </td>
                <td class="center">
                    <div id="yes" class="none" ><font color="#33cc00">__Passwords are equal__</font></div>
                    <div id="no"  class="none"><font color="#ff0000">__Passwords are not equal__</font></div>
                </td>
            </tr>
            <tr class="light">
                <td colspan="3" class="center">
                    <a href="{MODULE}user.pm&for=$user">__Private message__</a>
                </td>
            </tr>
        </table>
        <p class="center">
            <input type="hidden" name="act" value="profile.save" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</fieldset>
