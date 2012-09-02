<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com|
# MODULE USER - USER PANEL TEMPLATE

die();?>
[ifelse=loggedin]
    <div class="center"><strong>[__Hello], {user}!</strong></div>
    <div class="user_panel">
        <form method="post" action="">
            <ul class="links">
                [if=admin]
                    <li>
                        <img src="{ICONS}admin.png" width="16" height="16" alt="" />
                        <a href="{MODULE}admin&amp;id=main">[__Administration]</a>
                    </li>
                [endif]
                <li>
                    <img src="{ICONS}post.png" width="16" height="16" alt="" />
                    <a href="{MODULE}posts.post">[__Post]</a>
                </li>
                <li>
                    <img src="{ICONS}profile.png" width="16" height="16" alt="" />
                    <a href="{MODULE}user">[__Profile]</a>
                </li>
                <li>
                    <img src="{ICONS}messages.png" width="16" height="16" alt="" />
                    <a href="{MODULE}user.pm[if=mess_new]&amp;mode=inbox[endif]" title="{mess_info}">
                        [__Messages] [if=mess_new]<strong>({mess_new})</strong>[endif]
                    </a>
                </li>
            </ul>
            <p class="center"><input type="submit" name="logout" value="[__Log out]" class="submit" /></p>
        </form>
    </div>
[else]
    <script type="text/javascript">
    // form validation
    function checkLoginForm(form) {
        var username = form.username.value;
        var password = form.password.value;
        var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        if (username == "") {
            inlineMsg('username', '[__Enter your login]');
            return false;
        }
        if (!username.match(nameRegex)) {
            inlineMsg('username', '[__You have used an invalid symbols]');
            return false;
        }
        if (password == "") {
            inlineMsg('password', '[__Enter your password]');
            return false;
        }
        return true;
    }
    </script>
    <div class="login_panel">
        <ul class="links">
            <li>
                <img src="{ICONS}login.png" width="16" height="16" alt="" />
                <a href="#" onclick="document.getElementById('login').style.display = ShowHide(document.getElementById('login').style.display)">[__Log in]</a>
            </li>
            <li id="login" style="display:none;">
                <form id="login" name="login" method="post" action="" onsubmit="return checkLoginForm(this);">
                    <table>
                        <tr><td>[__Username]:</td><td><input type="text" name="username" id="username" size="15" /></td></tr>
                        <tr><td>[__Password]:</td><td><input type="password" name="password" id="password" size="15"/></td></tr>
                    </table>
                    <p class="center"><input type="submit" name="login" value="[__Log in]" class="submit" /></p>
                </form>
            </li>
            <li>
                <img src="{ICONS}forgetpass.png" width="16" height="16" alt="" />
                <a href="{MODULE}user&amp;act=password_request">[__Password recovery]</a>
            </li>
            <li>
                <img src="{ICONS}register.png" width="16" height="16" alt="" />
                <a href="{MODULE}user&amp;act=register">[__Registration]</a>
            </li>
        </ul>
    </div>
[endelse]
[if=allow_skins]<div class="center"><form name="skin_select" method="post" action="">{select_skin}</form></div>[endif]
[if=allow_langs]<div class="center"><form name="lang_select" method="post" action="">{select_lang}</form></div>[endif]
