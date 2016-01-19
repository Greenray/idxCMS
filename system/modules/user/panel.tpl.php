<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module USER: User panel

die();?>

<!-- IF !empty($logged_in) -->
    <div class="center"><strong>__Hello__, $user !</strong></div>
    <div class="user_panel">
        <form method="post" action="">
            <ul>
                <!-- IF !empty($admin) -->
                    <li>
                        <img src="{ICONS}admin.png" width="16" height="16" alt="" />
                        <a href="{MODULE}admin">__Administration__</a>
                    </li>
                <!-- ENDIF -->
                <li>
                    <img src="{ICONS}post.png" width="16" height="16" alt="" />
                    <a href="{MODULE}posts.post">__Post__</a>
                </li>
                <li>
                    <img src="{ICONS}profile.png" width="16" height="16" alt="" />
                    <a href="{MODULE}user">__Profile__</a>
                </li>
                <li>
                    <img src="{ICONS}messages.png" width="16" height="16" alt="" />
                    <a href="{MODULE}user.pm<!-- IF !empty($mess_new) -->&mode=inbox<!-- ENDIF -->" title="$mess_info">
                        __Messages__ <!-- IF !empty($mess_new) --><strong>($mess_new)</strong><!-- ENDIF -->
                    </a>
                </li>
            </ul>
            <p class="center"><input type="submit" name="logout" value="__Log out__" /></p>
        </form>
    </div>
<!-- ELSE -->
    <script type="text/javascript">
        function checkLoginForm(form) {
            var username = form.user.value;
            var password = form.password.value;
            var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
            if  (username === '') {
                ShowAlert('__Enter your login__');
                return false;
            }
            if (!username.match(nameRegex)) {
                ShowAlert('__Invalid login__');
                return false;
            }
            if (password === "") {
                ShowAlert('__Enter your password__');
                return false;
            }
            return true;
        }
        function checkPasswordRecoveryForm(form) {
            if (form.name.value === '') {
                ShowAlert('__Enter your login__');
                return false;
            }
            var email = form.email.value;
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if ((email === '') || !email.match(emailRegex)) {
                ShowAlert('__Error in email__');
                return false;
            }
            <!-- IF !empty($captcha) -->
                if (form.captcheckout.value === '') {
                    ShowAlert('__Enter a code__');
                    return false;
                }
            <!-- ENDIF -->
            return true;
        }
    </script>
    <div class="login-panel">
        <ul>
            <li>
                <img src="{ICONS}login.png" width="16" height="16" alt="" />
                <a href="#" onclick="document.getElementById('login').style.display = ShowHide(document.getElementById('login').style.display)">[__Log in]</a>
            </li>
            <li id="login" style="display:none;">
                <form id="login" name="login" method="post" action="" onsubmit="return checkLoginForm(this);">
                    <table>
                        <tr>
                            <td>__Username__:</td>
                            <td><input type="text" id="user" name="user" size="15" /></td>
                        </tr>
                        <tr>
                            <td>__Password__:</td>
                            <td><input type="password" id="password" name="password" size="15"/></td>
                        </tr>
                    </table>
                    <p class="center"><input type="submit" name="login" value="__Log in__" /></p>
                </form>
            </li>
            <li>
                <img src="{ICONS}forgetpass.png" width="16" height="16" alt="" />
                <a href="##" onclick="document.getElementById('password_request').style.display = ShowHide(document.getElementById('password_request').style.display)">[__Password recovery]</a>
            </li>
            <li id="password_request" style="display:none;">
                <form id="password_request" name="password_request" method="post" action="" onsubmit="return checkPasswordRecoveryForm(this);">
                    <table cellpadding="2" cellspacing="1" style="width:100%;">
                        <tr>
                            <td>__Username__:</td>
                            <td><input type="text" id="name" name="name" size="15" /></td>
                        </tr>
                        <tr>
                            <td>__E-mail__:</td>
                            <td><input type="text" id="email" name="email" size="15" /></td>
                        </tr>
                        <tr>
                            <td class="center" colspan="2">
                                <p>
                                    $captcha
                                    <input type="submit" name="save" value="__Submit__" />
                                </p>
                            </td>
                        </tr>
                    </table>
                </form>
            </li>
            <li>
                <img src="{ICONS}register.png" width="16" height="16" alt="" />
                <a href="{MODULE}user&act=register">__Registration__</a>
            </li>
        </ul>
    </div>
<!-- ENDIF -->
<!-- IF !empty($allow_skins) -->
    <div class="center"><form name="skin_select" method="post" action="">$select_skin</form></div>
<!-- ENDIF -->
<!-- IF !empty($allow_languages) -->
    <div class="center"><form name="lang_select" method="post" action="">$select_lang</form></div>
<!-- ENDIF -->
