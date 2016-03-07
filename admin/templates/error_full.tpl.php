<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Template for error message

die();?>

<!DOCTYPE html>
<html lang="$locale">
<head>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="{SKINS}normalize.css" media="screen" />
    <style type="text/css">
        html { background: #444 }
        form {
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.8);
            -moz-box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.8);
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.8);
        }
        .error {
            background: white;
            background-image: -webkit-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: -moz-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: -o-linear-gradient(to bottom, white 1%, #999 99%);
            background-image: linear-gradient(to bottom, white 1%, #999 99%);
            font-weight: bold;
            margin: 50px auto;
            min-width: 300px;
            padding: 10px;
            position: relative;
            width: 300px }
        .error p { padding: 10px; text-align: center }
        .error p.title {
            background: red;
            color: yellow;
            font-size: 14px;
            font-weight: bold;
            text-shadow: -1px 1px 1px rgba(0, 0, 0, 0.8);
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
    </style>
</head>
<body>
<form id="error" name="error" method="post" action="$url" class="error center">
    <p class="title">__Error__</p>
    <p class="text center">__$message__</p>
    <p class="center"><input type="submit" name="error" value="__OK__" /></p>
</form>
</body>
</html>