<?php
# idxCMS Flat Files Content Management System
# Version 3.0
# Copyright (c) 2016 Victor Nabatov
# Skin Default: main template

die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>[show=title]</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="$locale">
    <meta name="resource-type" content="document">
    <meta name="document-state" content ="dynamic">
    <meta http-equiv="pragma" content="no-cache">
    <meta name="robots" content="index, follow">
    <meta name="revisit" content="7">
    <meta name="generator" content="idxCMS">
    [show=meta]
    <link rel="stylesheet" href="{SKINS}css.php?f={SKINS}normalize|{CURRENT_SKIN}style|{TOOLS}message{DS}message|{TOOLS}colorbox{DS}jquery.colorbox">
    <script type="text/javascript" src="{TOOLS}jquery.min.js"></script>
    <link type="image/x-icon" rel="shortcut icon" href="{ROOT}favicon.ico" />
</head>
<body>
<div id="wrapper">
    <div class="header">
        <div class="toolbar">
            <ul>
                <li><a href="{MODULE}user.feedback">__Feedback__</a></li>
                <li><a href="{MODULE}sitemap">__Sitemap__</a></li>
                <li><a href="{MODULE}rss.list" title="Subscribe to RSS feeds"><img src="{IMAGES}rss.png" height="24" width="24" alt="RSS" /></a></li>
            </ul>
        </div>
        <div class="logo-image">
            <img src="{IMAGES}logo.png" width="350" height="88" alt="idxCMS" />
            Flat Files Content Management System
        </div>
        <div class="logo-version">{IDX_VERSION}</div>
        [show=box,menu@empty]
        <div class="search">[show=box,search@empty]</div>
        <!-- IF !empty($slogan) -->
            <div class="slogan">$slogan</div>
        <!-- ENDIF -->
	</div>
    <div class="content">
        <div class="mainbar">
            [show=point,up-center@main]
            [show=main,{module}@main]
            [show=point,down-center@main]
        </div>
        <div class="sidebar">
            [show=box,user.panel@box]
            [show=point,{module}@win]
            [show=point,right@panel]
        </div>
        <div class="clear"></div>
    </div>
    <div class="fbg">
        <div class="col c1">
            [show=box,gallery.preview@win]
        </div>
        <div class="col c2">
            [show=box,aphorisms@win]
        </div>
        <div class="col c3">
            [show=box,tagcloud@win]
        </div>
        <div class="clear"></div>
    </div>
    <div class="fbg">
        <div class="col c1">
            [show=box,polls@panel]
        </div>
        <div class="col c2">
            [show=box,minichat@panel]
        </div>
        <div class="col c3">
            [show=box,counter@panel]
        </div>
        <div class="clear"></div>
    </div>
</div>
[show=box,menu.simple@empty]
<div class="footer">
    <div class="valid center">
        <a href="./" class="valid-icon valid-icon-cms"></a>
        <a href="http://www.php.net" class="valid-icon valid-icon-php"></a>
        <a href="http://ru.wikipedia.org/wiki/NoSQL" class="valid-icon valid-icon-db"></a>
        <a href="http://validator.w3.org/check?uri=referer" class="valid-icon valid-icon-xhtml"></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer" class="valid-icon valid-icon-css"></a>
        <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/" class="valid-icon valid-icon-license"></a>
    </div>
    <div class="copyright">[show=copyright]</div>
</div>
<script type="text/javascript" src="{TOOLS}jquery.rating.min.js"></script>
<script type="text/javascript" src="{TOOLS}colorbox{DS}jquery.colorbox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".cbox").colorbox({rel:'cbox'});
    });
</script>
<script src="{TOOLS}jquery.lightbox_me.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $("#error").lightbox_me();
        $("#message").lightbox_me();
    });
</script>
<script type="text/javascript" src="{TOOLS}message{DS}message.min.js"></script>
<script type="text/javascript">
    function ShowHide(obj) {
        if (obj === "none")
             return "inline";
        else return "none";
    }
    function ShowAlert(msg) {
        dhtmlx.modalbox({
            type: "alert-error",
            title: "__Error__",
            text: "<strong>" + msg + "</strong>",
            buttons: ["Ok"]
        });
    }
</script>
<!--[if lt IE 7]><script type="text/javascript" src="{TOOLS}unitip{DS}unitpngfix.js"></script><![endif]-->
<script type="text/javascript" src='{TOOLS}unitip{DS}unitip.min.js'></script>
</body>
</html>
