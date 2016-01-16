<?php
# idxCMS Flat Files Content Management System
# Version 3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Skin Default: main template

die();?>

<!DOCTYPE html>
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
    <link rel="stylesheet" type="text/css" href="{CURRENT_SKIN}style.css" media="screen" />
    <script type="text/javascript" src="{TOOLS}jquery.min.js"></script>
    <!-- IF !empty($index) -->
        <script type="text/javascript">
            $(function () {
                function makeTabs(id) {
                    var tabContainers = $('#' + id + ' div.tabs > div > div.tab_content');
                    tabContainers.hide().filter(':last').show();
                    $('#' + id + ' div.tabs ul.tabs a').click(function () {
                        tabContainers.hide();
                        tabContainers.filter(this.hash).show();
                        $('#' + id + ' div.tabs ul.tabs a').removeClass('selected');
                        $(this).addClass('selected');
                        return false;
                    }).filter(':last').click();
                }
                for (var i = 1; i <= 5; i++) {
                    makeTabs('tabs-' + i);
                }
            });
        </script>
    <!-- ENDIF -->
    <script type="text/javascript" src="{TOOLS}jquery.rating.js"></script>
    <link rel="stylesheet" type="text/css" href="{TOOLS}message{DS}message.css" media="screen" />
    <script type="text/javascript" src='{TOOLS}message{DS}message.js'></script>
    <script type="text/javascript">
        function ShowHide(obj) {
            if (obj === 'none')
                 return 'inline';
            else return 'none';
        }
        function ShowAlert(msg, title) {
            dhtmlx.modalbox({
                type: 'alert-error',
                title: title,
                text: '<strong>' + msg + '</strong>',
                buttons: ["Ok"]
            });
        }
    </script>
    <link type="image/x-icon" rel="shortcut icon" href="{ROOT}favicon.ico" />
</head>
<body>
<div id="wrapper">
    <div class="header">
        <div class="header_resize">
            <div class="logo"><img src="{IMAGES}logo.png" width="350" height="99" alt="idxCMS" />
                <div class="tagline">Flat Files Content Management System</div>
            </div>
            <div id="toolbar">
                <ul>
                    <li><a href="{MODULE}user.feedback">__Feedback__</a></li>
                    <li><a href="{MODULE}sitemap">__Sitemap__</a></li>
                    <li><a href="{MODULE}rss.list" title="Subscribe to RSS feeds"><img src="{ICONS}rss.png" height="24" width="24" alt="RSS" /></a></li>
                </ul>
            </div>
             <div class="search">[show=box,search@empty]</div>
              <div class="clear"></div>
             <div id="menu">
                [show=box,menu@empty]
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="content">
        <div class="content_resize">
            <div class="mainbar">
                [show=point,up-center@main]
                [show=main,{module}@main]
                [show=point,down-center@main]
            </div>
            <div class="sidebar">
                <div class="gadget">
                    [show=error]
                    [show=box,user.panel@box]
                    [show=point,{module}@win]
                    [show=point,right@panel]
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="fbg">
        <div class="col c1">
            <div class="title center"><h2><span>Image</span> Gallery</h2></div>
            <a href="#"><img src="{IMAGES}gal1.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="{IMAGES}gal2.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="{IMAGES}gal3.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="{IMAGES}gal4.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="{IMAGES}gal5.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="{IMAGES}gal6.jpg" width="75" height="75" alt="" class="gal" /></a> </div>
        <div class="col c2">
            [show=box,aphorisms@win]
        </div>
        <div class="col c3">
            [show=box,banners@win]
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
        <a href="./"><img src="{IMAGES}cms.gif" width="80" height="15" alt="idxCMS" /></a>
        <a href="http://www.php.net"><img src="{IMAGES}php.gif" width="80" height="15" alt="PHP powered" /></a>
        <a href="http://ru.wikipedia.org/wiki/NoSQL"><img src="{IMAGES}db.gif" width="80" height="15" alt="No SQL" /></a>
        <a href="http://validator.w3.org/check?uri=referer"><img src="{IMAGES}html.gif" width="80" height="15" alt="Valid HTML!" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="{IMAGES}css.gif" width="80" height="15" alt="Valid CSS!" /></a>
        <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img src="{IMAGES}cc.png" width="80" height="15" alt="Creative Commons License" /></a>
    </div>
    <div class="copyright">[show=copyright]</div>
</div>
</body>
</html>
