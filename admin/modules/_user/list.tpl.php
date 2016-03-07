<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: User's list.

die();?>

<div class="module">__User profile__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr class="light"><td colspan="3" class="center">__Do not delete user, just block him to keep the structure of site__</td></tr>
            <tr>
                <th style="width:20%">__Login__</th>
                <th style="width:20%">__Nick__</th>
                <th style="width:60%">__Actions__</th>
            </tr>
            <!-- FOREACH user = $users -->
                <tr class="light">
                    <td>$user.user</td>
                    <!-- IF !empty($user.nick) -->
                        <td>$user.nick</td>
                        <td>
                            <input type="radio" name="act" value="profile.$user.user" /> __Profile__
                            <input type="radio" name="act" value="rights.$user.user" /> __Rights__
                            <input type="radio" name="act" value="$user.blocked" /> $user.blocking
                            <input type="radio" name="act" value="delete.$user.user" /> __Delete__
                        </td>
                    <!-- ELSE -->
                        <td colspan="2" class="help">__User data is unreadable or corrupt__</td>
                    <!-- ENDIF -->
                </tr>
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="submit" value="__Submit__" /></p>
    </form>
</fieldset>
