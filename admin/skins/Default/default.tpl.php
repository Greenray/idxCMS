<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Skin "Default".

if (!defined('idxADMIN')) die();?>

<!DOCTYPE html>
<html lang="$locale">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="generator" content="idxCMS">
    <title>__Administration__</title>
    <link rel="stylesheet" type="text/css" href="{SKINS}normalize.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{ADMIN}skins/Default/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{TOOLS}message{DS}message.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{TOOLS}redips{DS}redips.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{TOOLS}unitip{DS}unitip.css" media="screen" />
    <script type="text/javascript" src="{TOOLS}jquery.min.js"></script>
</head>
<body onload="ClickMenu('menu')">
<div id="page">
    <header>
        <div class="logo">
            <img src="{IMAGES}logo.png" width="350" height="99" alt="idxCMS" />
            <span class="version">{IDX_VERSION}</span>
            <div class="logo_desc">Flat Files Content Management System</div>
        </div>
    </header>
    <nav>
        <ul id="menu">
            <li class="sub">&#0187; __Return to__ ...
                <ul>
                    <li class="popup">
                        <a href="{ROOT}" target="_top">... __site index__</a>
                        <ul>
                        <!-- FOREACH menu = $menu -->
                            <li><a href="$menu.link" class="icon icon-$menu.class" target="_top">$menu.name</a></li>
                        <!-- ENDFOREACH -->
                        </ul>
                    </li>
                    <li><a href="{MODULE}admin" target="main"> ... __admin index__</a></li>
                </ul>
            </li>
            <!-- FOREACH mods = $modules -->
                <!-- IF !empty($mods.module) -->
                    <li class="sub">
                        $mods.name
                        <ul>
                        <!-- FOREACH mod = $mods.module -->
                            <li><a href="{MODULE}admin&id=$mod.module.$mod.category" target="main">$mod.title</a></li>
                        <!-- ENDFOREACH -->
                        </ul>
                    </li>
                <!-- ENDIF -->
             <!-- ENDFOREACH -->
        </ul>
    </nav>
	<div id="posts">
        <article>
            $page
        </article>
	</div>
	<footer><p class="center">{IDX_POWERED} {IDX_COPYRIGHT}</p></footer>
</div>
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
    // Copyright (c) 2005-2007 Stu Nicholls. All rights reserved.
    // The original version of this script and the associated (x)html is available at http://www.stunicholls.com/menu/tree_frog_vertical.html
    // This script and the associated (x)html may be modified inany way to fit your requirements.
    // This copyright notice must be untouched at all times.
    var ClickMenu = function(menu) {
        var getEls = document.getElementById(menu).getElementsByTagName("LI");
        var getAgn = getEls;
        for (var i = 0; i < getEls.length; i++) {
            getEls[i].onclick = function() {
                for (var x = 0; x < getAgn.length; x++) {
                    getAgn[x].className = getAgn[x].className.replace("unclick", "");
                    getAgn[x].className = getAgn[x].className.replace("click", "unclick");
                }
                if ((this.className.indexOf("unclick")) !== -1) {
                    this.className = this.className.replace("unclick", "");
                } else {
                    this.className += " click";
                }
            };
            getEls[i].onmouseover = function() {
                this.className += " hover";
            };
            getEls[i].onmouseout = function() {
                this.className = this.className.replace("hover", "");
            };
        }
    };
</script>
<script type="text/javascript" src='{TOOLS}unitip{DS}unitip.min.js'></script>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</body>
</html>