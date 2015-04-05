<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__User profile]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <tr class="odd"><td colspan="3" class="center">[__Do not delete user, just block him to keep the structure of site]</td></tr>
            <tr>
                <th style="width:20%">[__Login]</th>
                <th style="width:20%">[__Nick]</th>
                <th style="width:60%">[__Actions]</th>
            </tr>
            [each=user]
            <tr class="odd">
                <td>{user[username]}</td>
                <td>{user[nickname]}</td>
                <td>
                    <label><input type="radio" name="act" value="profile.{user[username]}" /> [__Profile]</label>
                    <label><input type="radio" name="act" value="rights.{user[username]}" /> [__Rights]</label>
                    <label><input type="radio" name="act" value="{user[blocked]}" /> {user[blocking]}</label>
                    <label><input type="radio" name="act" value="delete.{user[username]}" /> [__Delete]</label>
                </td>
            </tr>
            [endeach.user]
        </table>
        <p class="center"><input type="submit" name="submit" value="[__Submit]" class="submit" /></p>
    </form>
</fieldset>
