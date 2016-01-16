<?php
# idxCMS Flat Files Content Management System
# Version 3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# User panel template

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
                    <a href="{MODULE}user.pm<!-- IF !empty($mess_new) -->&amp;mode=inbox<!-- ENDIF -->" title="$mess_info">
                        __Messages__ <!-- IF !empty($mess_new) --><strong>($mess_new)</strong><!-- ENDIF -->
                    </a>
                </li>
            </ul>
            <p class="navigation center"><input type="submit" name="logout" value="__Log out__" /></p>
        </form>
    </div>
<!-- ELSE -->
    <script src="{TOOLS}jquery.lightbox_me.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $("#enter").click(function() {
                $("#login").lightbox_me({onLoad: function() {
                    $("#login").find("input:first").focus();
                }});
            });
            $("#recovery").click(function() {
                $("#password_recovery").lightbox_me({onLoad: function() {
                    $("#password_recovery").find("input:first").focus();
                }});
            });
        });
    </script>
    <script type="text/javascript">
        function checkLoginForm(form) {
            var username = form.user.value;
            var password = form.password.value;
            var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
            if  (username === '') {
                ShowAlert('__Invalid login__');
                return false;
            }
            if (!username.match(nameRegex)) {
                ShowAlert('__Invalid username__');
                return false;
            }
            if (password === "") {
                ShowAlert('__Enter your password__');
                return false;
            }
            return true;
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
                <a href="#" id="enter">__Log in__</a>
                <form id="login" name="login" method="post" action="" onsubmit="return checkLoginForm(this);" class="login">
                    <h1><span class="log-in">__Log in__</span></h1>
                    <p class="float">
                        <label for="user">__Username__</label>
                        <input type="text" name="user" id="user" placeholder="__Login__" />
                    </p>
                    <p class="float">
                        <label for="password">__Password__</label>
                        <input type="password" name="password" id="password" placeholder="__Password__" class="showpassword" />
                    </p>
                    <p class="navigation"><input type="submit" name="login" value="__Log in__" /></p>
                    <a href="#" class="close"></a>
                </form>
            </li>
            <li>
                <img src="{ICONS}forgetpass.png" width="16" height="16" alt="" />
                <a href="##" id="recovery">__Password recovery__</a>
                <form id="password_recovery" name="password_recovery" method="post" action="{MODULE}user&amp;act=password_request" onsubmit="return checkPasswordRecoveryForm(this);" class="password_recovery">
                    <h1><span class="password-recovery">__Password recovery__</span></h1>
                    <p class="float">
                        <label for="user">__Username__</label>
                        <input type="text" name="user" id="user" placeholder="__Login__" />
                    </p>
                    <p class="float">
                        <label for="email">__E-mail__</label>
                        <input type="text" name="email" id="email" placeholder="__E-mail__" />
                    </p>
                    <p class="navigation"><input type="submit" name="save" value="__Submit__" /></p>
                    <a href="#" class="close"></a>
                </form>
            </li>
            <li>
                <img src="{ICONS}register.png" width="16" height="16" alt="" />
                <a href="{MODULE}user&amp;act=register">__Registration__</a>
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
