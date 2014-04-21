<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - PROFILE TEMPLATE

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
</script>
<div class="module">[__User profile]</div>
<fieldset>
    <form name="profile" id="profile" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td class="right">[__Username]</td>
                <td colspan="2" class="left">
                    <input type="hidden" name="username" value="{username}" />
                    {username}
                </td>
            </tr>
            <tr class="odd">
                <td class="right">[__Nick]</td>
                <td colspan="2" class="left"><input type="text" name="nickname" id="nickname" value="{nickname}" class="required" /></td>
            </tr>
            <tr class="odd"><td class="right">[__Avatar]</td><td colspan="2" class="center"><img src="{avatar}" hspace="5" vspace="5" alt="" /></td></tr>
            <tr class="odd">
                <td class="right">[__E-mail]</td>
                <td  colspan="2"><input type="text" name="email" id="email" value="{email}" class="required" /></td>
            </tr>
            <tr class="odd"><td class="right">[__Time zone]</td><td colspan="2" class="left">{utz}</td></tr>
            <tr class="odd">
                <td class="right">[__Personal status]</td>
                <td colspan="2"><input type="text" name="status" value="{status}" /></td>
            </tr>
            <tr class="odd"><td class="right">[__Rate]</th><td colspan="2" class="left">{stars}</td></tr>
            <tr class="odd">
                <td class="right">[__Access level]</td>
                <td colspan="2"><input type="text" name="access" id="access" value="{access}" class="required" /></td>
            </tr>
            <tr class="odd"><td class="right">[__Registration]</td><td colspan="2">{regdate}</td></tr>
            <tr class="odd"><td class="right">[__Last visit]</td><td colspan="2">{lastvisit}</td></tr>
            <tr class="odd"><td class="right">[__Visits]</td><td colspan="2">{visits}</td></tr>
            <tr class="odd"><td class="right">[__Posts]</td><td colspan="2">{posts}</td></tr>
            <tr class="odd"><td class="right">[__Comments]</td><td colspan="2">{comments}</td></tr>
            <tr class="odd"><td class="right">[__Topics]</td><td colspan="2">{topics}</td></tr>
            <tr class="odd"><td class="right">[__Replies]</td><td colspan="2">{replies}</td></tr>
            <tr class="odd">
                <td class="right">[__ICQ]</td>
                <td colspan="2" class="left"><input type="text" name="fields[icq]" value="{icq}" size="12" /></td>
            </tr>
            <tr class="odd">
                <td class="right">[__Website]</td>
                <td colspan="2" class="left"><input type="text" name="fields[website]" value="{website}" size="50" /></td>
            </tr>
            <tr class="odd">
                <td class="right">[__Country]</td>
                <td colspan="2" class="left"><input type="text" name="fields[country]" value="{country}" size="25" /></td>
            </tr>
            <tr class="odd">
                <td class="right">[__City]</td>
                <td colspan="2" class="left"><input type="text" name="fields[city]" value="{city}" size="25" /></td>
            </tr>
            <tr class="odd">
                <td class="right">[__Blocked]</td>
                <td colspan="2" class="left"><input type="checkbox" name="blocked" value="1" [if=blocked] checked="checked" [endif] /></td>
            </tr>
            <tr class="even">
                <td>[__Password]</td>
                <td colspan="2" class="left"><input type="password" name="password" onkeyup="check('profile');" value="" /></td>
            </tr>
            <tr class="even">
                <td>[__Confirm password]</td>
                <td class="left"><input type="password" name="confirm" onkeyup="check('profile');" value="" /></td>
                <td class="center">
                    <div id="yes" class="none" ><font color="#33cc00">[__Passwords are equal]</font></div>
                    <div id="no"  class="none"><font color="#ff0000">[__Passwords are not equal]</font></div>
                </td>
            </tr>
            <tr class="odd"><td colspan="3" class="center"><a href="{MODULE}user.pm&amp;for={username}">[__Private message]</a></td></tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
