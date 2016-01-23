<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Skin DEFAULT: Main template

die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<head>
    <title>[show=title]</title>
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
    <link rel="shortcut icon" type="image/x-icon" href="{ROOT}favicon.ico" />
</head>
<body>
<div id="wrapper">
    <div class="header">
        <div class="toolbar right">
            <ul>
                <li><a href="{MODULE}user.feedback">__Feedback__</a></li>
                <li><a href="{MODULE}sitemap">__Sitemap__</a></li>
                <li>
                    <a href="{MODULE}rss.list" class="icon icon-rss tip" title="Subscribe to RSS feeds">
                        <img src="{IMAGES}rss.png" height="24" width="24" alt="RSS" />
                    </a>
                </li>
            </ul>
        </div>
        <div class="logo-image center">
            <img src="{IMAGES}logo.png" width="350" height="88" alt="idxCMS" />
            Flat Files Content Management System
        </div>
        <div class="logo-version">{IDX_VERSION}</div>
        <div class="search">[show=box,search@empty]</div>
        <!-- IF !empty($slogan) -->
            <div class="slogan">$slogan</div>
        <!-- ENDIF -->
	</div>
    [show=box,menu@empty]
    <div class="page">
        <div id="layout-root">
            <div id="layout-left">
                [show=point,{CURRENT_MODULE}@win]
            </div>
            <div id="layout-right">
                [show=box,user.panel@panel]
                [show=point,right@box]
            </div>
            <div id="layout-center">
                [show=point,up-center@main]
                [show=main,{CURRENT_MODULE}@main]
                [show=point,down-center@main]
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="boxes">
    <div class="leftbox">
        <div class="darkbox">
            <div class="title center"><h3>Контакты</h3></div>
            <div class="content">
                <ul>
                    <li><a href="{MODULE}user.feedback">__Feedback__</a></li>
                    <li><a href="{MODULE}rss.list" title="Subscribe to RSS feeds">__RSS feeds__</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="middlebox">
        [show=box,aphorisms@darkbox]
        <div class="clear"></div>
    </div>
    <div class="rightbox">
        [show=box,counter@darkbox]
    </div>
    <div class="clear"></div>
</div>
[show=box,menu.simple@empty]
<div class="footer">
    <div class="valid center">
        <a href="./"><img src="{IMAGES}cms.gif" width="80" height="15" alt="idxCMS" /></a>
        <a href="http://www.php.net"><img src="{IMAGES}php.gif" width="80" height="15" alt="PHP powered" /></a>
        <a href="http://ru.wikipedia.org/wiki/NoSQL"><img src="{IMAGES}db.gif" width="80" height="15" alt="No SQL" /></a>
        <a href="http://validator.w3.org/check?uri=referer"><img src="{IMAGES}html.gif" width="80" height="15" alt="Valid HTML!" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="{IMAGES}css.gif" width="80" height="15" alt="Valid CSS!" /></a>
        <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img src="{IMAGES}cc.png" width="80" height="15" alt="Creative Commons License" /></a>
    </div>
    <div class="copyright center">[show=copyright]</div>
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
<script type="text/javascript" src='{TOOLS}message{DS}message.min.js'></script>
<script type="text/javascript">
    function ShowHide(obj) {
        if (obj === 'none')
             return 'inline';
        else return 'none';
    }
    function ShowAlert(msg) {
        dhtmlx.modalbox({
            type: 'alert-error',
            title: '__Error__',
            text: '<strong>' + msg + '</strong>',
            buttons: ["Ok"]
        });
    }
</script>
<!--[if lt IE 7]><script type="text/javascript" src="{TOOLS}unitip{DS}unitpngfix.js"></script><![endif]-->
<script type="text/javascript" src='{TOOLS}unitip{DS}unitip.min.js'></script>
</body>
</html>
