<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# SKIN DEFAULT

if (!defined('idxCMS')) die();?>
<!DOCTYPE html>
<html lang="{locale}">
<head>
    <title>[show=title]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="{locale}" />
    [show=meta]
    <link href="{CURRENT_SKIN}style.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="{TOOLS}message.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="{TOOLS}jquery.js"></script>
    [if=index]
        <script type="text/javascript">
            $(function () {
                function makeTabs(id) {
                    var tabContainers = $('#' + id + ' div.tabs > div');
                    tabContainers.hide().filter(':last').show();
                    $('#' + id + ' div.tabs ul.tabs a').click(function () {
                        tabContainers.hide();
                        tabContainers.filter(this.hash).show();
                        $('#' + id + ' div.tabs ul.tabs a').removeClass('selected');
                        $(this).addClass('selected');
                        return false;
                    }).filter(':last').click();
                }
                for (i = 1; i <= {tabs}; i++) {
                    makeTabs('tabs-' + i);
                }
            });
        </script>
    [endif]
    <link href="{TOOLS}colorbox/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="{TOOLS}colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".cbox").colorbox({rel:'cbox'});
        });
    </script>
    <script type="text/javascript" src="{TOOLS}jquery.rating-min.js"></script>
    <script type="text/javascript" src="{TOOLS}unitip-min.js"></script>
    <script type="text/javascript" src="{TOOLS}message-min.js"></script>
    <script type="text/javascript" src='{TOOLS}message.js'></script>
    <script type="text/javascript">
        function ShowHide(obj) {
            if (obj == "none") {
                return "inline";
            } else {
                return "none";
            }
        }
    </script>
    <link type="image/x-icon" rel="shortcut icon" href="favicon.ico" />
</head>
<body id="page">
<div id="wrapper">
    <div id="header">
        <div id="toolbar">
            <ul>
                <li><a href="{MODULE}user.feedback">[__Feedback]</a></li>
                <li><a href="{MODULE}sitemap">[__Sitemap]</a></li>
                <li>
                    <a href="{MODULE}rss.list" title="Subscribe to RSS feeds">
                        <img src="{ICONS}rss.png" height="24" width="24" alt="RSS" />
                    </a>
                </li>
            </ul>
        </div>
        <div id="top-navigation">
            [show=box,menu.navigation@empty]
            <div id="slogan">{slogan}</div>
        </div>
        <div id="logo">
            <a href="{ROOT}" title="[__Index]"><span class="idx">idx</span></a>
            <div class="version">Version {IDX_VERSION}</div>
        </div>
        <div class="search">[show=box,search@empty]</div>
        <div class="clear"></div>
        [show=box,menu@empty]
    </div>
    <div id="layout-root">
        <div id="layout-left">
            [show=point,{module}@win]
        </div>
        <div id="layout-right">
            [show=error]
            [show=box,user.panel@box]
            [show=point,right@panel]
        </div>
        <div id="layout-center">
            [show=point,up-center@main]
            [show=main,{module}@main]
            [show=point,down-center@main]
        </div>
    </div>
    <div class="clear"></div>
</div>
<div id="boxes">
    <div class="box-container">
        <div class="left">
            <div class="darkbox">
                <div class="title center">Контакты</div>
                <div class="content">
                    <ul>
                        <li><a href="{MODULE}user.feedback">[__Feedback]</a></li>
                        <li><a href="{MODULE}rss.list" title="Subscribe to RSS feeds">[__RSS feeds]</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="middle">
            [show=box,aphorisms@darkbox]
            <div class="clear"/></div>
        </div>
        <div class="right">
            [show=box,counter@darkbox]
        </div>
    </div>
    <div class="clear"></div>
</div>
[show=box,menu.simple@empty]
<div id="footer">
    <div id="valid">
        <a href="./"><img src="{IMAGES}cms.gif" width="80" height="15" alt="idxCMS" /></a>
        <a href="http://www.php.net"><img src="{IMAGES}php.gif" width="80" height="15" alt="PHP powered" /></a>
        <a href="http://ru.wikipedia.org/wiki/NoSQ"><img src="{IMAGES}db.gif" width="80" height="15" alt="No SQL" /></a>
        <a href="http://validator.w3.org/check?uri=referer"><img src="{IMAGES}html.gif" width="80" height="15" alt="Valid HTML!" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="{IMAGES}css.gif" width="80" height="15" alt="Valid CSS!" /></a>
        <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img src="{IMAGES}cc.png" width="80" height="15" alt="Creative Commons License" /></a>
    </div>
    <div id="copyright">[show=copyright]</div>
</div>
</body>
</html>
