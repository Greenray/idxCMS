<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: User panel

die();?>

<!-- IF !empty($logged_in) -->
    <div class="center"><strong>__Hello__, $user !</strong></div>
    <div class="user-panel">
        <form method="post" >
            <ul>
                <!-- IF !empty($admin) -->
                    <li><a href="{MODULE}admin" class="icon icon-admin"> __Administration__</a></li>
                <!-- ENDIF -->
                <li><a href="{MODULE}posts.post" class="icon icon-post"> __Post__</a></li>
                <li><a href="{MODULE}user" class="icon icon-profile"> __Profile__</a></li>
                <li>
                    <a href="{MODULE}user.pm<!-- IF !empty($mess_new) -->&mode=inbox<!-- ENDIF -->" title="$mess_info" class="icon icon-messages tip">
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
            var username  = form.user.value;
            var password  = form.password.value;
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
            <li><a href="#" class="icon icon-login" onclick="document.getElementById('login').style.display = ShowHide(document.getElementById('login').style.display)">__Log in__</a></li>
            <li id="login" style="display:none;">
                <form id="login-form" name="login" method="post" onsubmit="return checkLoginForm(this);">
                    <table>
                        <tr>
                            <td>__Username__:</td>
                            <td><input id="user" name="user" type="text" size="15" /></td>
                        </tr>
                        <tr>
                            <td>__Password__:</td>
                            <td><input id="password" name="password" type="password" size="15"/></td>
                        </tr>
                    </table>
                    <p class="center"><input type="submit" name="login" value="__Log in__" /></p>
                </form>
            </li>
            <li><a href="#" class="icon icon-password-request" onclick="document.getElementById('password_request').style.display = ShowHide(document.getElementById('password_request').style.display)">__Password recovery__</a></li>
            <li id="password_request" style="display:none;">
                <form id="password_request-form" name="password_request" method="post"  onsubmit="return checkPasswordRecoveryForm(this);">
                    <table>
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
            <li><a href="{MODULE}user&amp;act=register" class="icon icon-registration">__Registration__</a></li>
        </ul>
    </div>
<!-- ENDIF -->
<!-- IF !empty($allow_skins) -->
    <div class="center"><form name="skin_select" method="post" >$select_skin</form></div>
<!-- ENDIF -->
<!-- IF !empty($allow_languages) -->
    <div class="center"><form name="lang_select" method="post" >$select_lang</form></div>
<!-- ENDIF -->
