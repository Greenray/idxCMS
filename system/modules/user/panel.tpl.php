<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com|
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
    <script src="{TOOLS}jquery.lightbox_me.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function() {
			$('#enter').click(function(e) {
				$(".login").lightbox_me({centered: true, onLoad: function() {
					$(".login").find("input:first").focus();
				}});
				e.preventDefault();
			});
		});
	</script>
	<link rel="stylesheet" href="{TOOLS}lightbox.css" type="text/css" media="screen">
    <script type="text/javascript">
    // Form validation
    function checkLoginForm(form) {
        var username = form.username.value;
        var password = form.password.value;
        var nameRegex = /^[a-zA-Z0-9_]+(([\_][a-zA-Z0-9])?[a-zA-Z0-9_]*)*$/;
        if  (username === '') {
            ShowAlert('[__Invalid login]', '[__Error]');
            return false;
        }
        if (!username.match(nameRegex)) {
            ShowAlert('[__Invalid username]', '[__Error]');
            return false;
        }
        if (password === "") {
            ShowAlert('[__Enter your password]', '[__Error]');
            return false;
        }
        return true;
    }
    </script>
    <div class="login_panel">
        <ul class="links">
            <li>
                <img src="{ICONS}login.png" width="16" height="16" alt="" />
                <a href="#" id="enter">[__Log in]</a>
                <form id="login" name="login" method="post" action="" onsubmit="return checkLoginForm(this);" class="login">
                    <h1><span class="log-in">[__Log in]</span></h1>
                    <p class="float">
                        <label for="login"><i class="icon-user"></i>[__Username]</label>
                        <input type="text" name="username" id="username" placeholder="[__Login]" />
                    </p>
                    <p class="float">
                        <label for="password"><i class="icon-lock"></i>[__Password]</label>
                        <input type="password" name="password" id="password" placeholder="[__Password]" class="showpassword" />
                    </p>
                    <p class="dhtmlx_popup_controls">
                        <div class='dhtmlx_popup_button'>
                            <div><input type="submit" name="login" value="[__Log in]" class="popup_input" /></div>
                        </div>
                    </p>
                    <a class="close" href="#"></a>
                </form>
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
