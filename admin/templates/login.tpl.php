<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Login form

die();?>

<!DOCTYPE html>
<html lang="$locale">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="{SKINS}normalize.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{TOOLS}message{DS}message.css" media="screen" />
    <style type="text/css">
        html { background: #444 }
        .denied {
            color: red;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase }
        input[type="submit"] {
            background: gray;
            background-image: -webkit-linear-gradient(to bottom, #909090 1%, #3d3d3d 50%, #404040 99%);
            background-image: -moz-linear-gradient(to bottom, #909090 1%, #3d3d3d 50%, #404040 99%);
            background-image: -o-linear-gradient(to bottom, #909090 1%, #3d3d3d 50%, #404040 99%);
            background-image: linear-gradient(to bottom, #909090 1%, #3d3d3d 50%, #404040 99%);
            border: 1px solid #838383;
            border: 1px solid white;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            -webkit-box-shadow: 1px 2px 4px black;
            -moz-box-shadow: 1px 2px 4px black;
            box-shadow: 1px 2px 4px black;
            color: white;
            font-weight: bold;
            line-height: 20px;
            margin: 5px;
            min-width: 100px;
            outline: none;
            padding: 0px 5px;
            position: relative;
            text-shadow: 1px 1px 1px black }
        input[type="submit"]:hover { color: red }
        .login {
            background: white;
            background-image: -webkit-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: -moz-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: -o-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: linear-gradient(to bottom, white 1%, #999 99%);
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.8);
            margin: 50px auto;
            padding: 5px;
            position: relative;
            text-align: center;
            width: 310px }
        .login h1 {
            background: #6e7b8b;
            color: #bdb5aa;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 0;
            text-shadow: -1px 1px 1px rgba(0, 0, 0, 0.8);
            text-transform: uppercase }
        .login h1 .log-in { color: white; display: inline-block; text-transform: uppercase }
        .login .float { float: left; padding: 5px }
        .login .float:first-of-type,
        .login .float:last-of-type { padding-left: 5px }
        .login .close {
            background: transparent url('../images/icons/delete.png') no-repeat;
            height: 16px;
            position: absolute;
            right: 20px;
            top: 35px;
            width: 16px }
        .login input[type="text"],
        .login input[type="password"] {
            background: #fdffbf;
            border: 1px solid #747270;
            border-radius: 5px;
            display: block;
            margin-bottom: 5px;
            padding: 5px;
            transition: all 0.5s ease-out;}
        .login input[type="text"]:focus,
        .login input[type="password"]:focus { border-color: #ff0000; outline: none }
        .login label {
            cursor: pointer;
            display: block;
            font-size: 11px;
            font-weight: bold;
            padding: 0 0 5px 2px;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
            text-transform: uppercase }
        .login label:hover ~ input { border-color: #ff0000 }
        .login label[for="showpassword"] {
            display: inline-block;
            font-size: 11px;
            font-weight: 400;
            margin-bottom: 10px;
            text-transform: capitalize }
    </style>
    <script type="text/javascript" src="{TOOLS}message{DS}message.js"></script>
    <script type="text/javascript">
        function ShowAlert(msg) {
            dhtmlx.modalbox({
                type: "alert-error",
                title: "__Error__",
                text: "<strong>" + msg + "</strong>",
                buttons: ["Ok"]
            });
        }
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
    </script>
</head>
<body>
<form id="login" name="login" method="post"  onsubmit="return checkLoginForm(this);" class="login">
    <span class="denied">__Access denied__</span>
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
</body>
</html>
