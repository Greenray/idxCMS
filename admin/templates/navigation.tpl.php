<!DOCTYPE html>
<head>
    <title>[__Navigation]</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="{locale}" />
    <style type="text/css">
        <!--
        body { background: #006699; color: yellow; font: normal 10pt arial, verdana, sans-serif; text-shadow: 1px 1px 1px black }
        #menu        { list-style-type: none; padding: 0; margin: 0; width: 190px; position: absolute; left: 0; border: 0; border-width: 0 1px 1px; z-index: 1 }
        #menu ul     { padding: 0; margin: 0 }
        #menu li     { background: #d1d7dc; position: relative; float: left; border-top: 1px solid white }
        #menu li.sub { background: #006699 url(./skins/images/icons/arrow.gif) no-repeat top right }
        #menu li.sub.click               { font-weight: bold }
        #menu li.sub.click ul li a       { color: black; text-shadow: none; font-weight: normal }
        #menu li.sub.click ul li.hover a { color: white; font-weight: bold }
        #menu li,
        #menu li a { display: block; color: white; line-height: 24px; width: 190px; text-decoration: none; text-indent: 5px; cursor: pointer }
        #menu ul,
        #menu li.click ul ul,
        #menu li.click ul li.hover ul ul { display: none }
        #menu li.hover                   { color: #ff0; z-index: 2 }
        #menu li.click                   { color: #ff0; background: #0066dd; text-align: center }
        #menu li.click ul                { display: block; text-align: left }
        #menu li.click ul li.hover ul,
        #menu li.click ul li.hover ul li.hover ul    { background: red; color: yellow; display: block; position: absolute; left: 60px; top: -1px; border: 1px solid white; border-width: 0 1px 1px }
        #menu li.click ul li.popup                   { background: #999 url(./skins/images/icons/arrow.gif) no-repeat top right }
        #menu li.click ul li.hover                   { background: red; color: white }
        #menu li.click ul li.hover ul li             { background: #356fcc }
        #menu li.click ul li.hover ul li.hover ul li { background: #780; z-index: 2 }
        #menu li.click ul li.hover ul li.popup       { background: #c60 url(./skins/images/icons/arrow.gif) no-repeat top right }
        #menu li.click ul li.hover ul li.hover       { background: yellow; z-index: 2 }
        #menu li.click ul li.hover ul li.hover a     { color: black }
        .page-title { font-size: 14pt; font-weight: bold; padding: 10px; text-align: center }
    -->
    </style>
    <script>
    <!--
    // Copyright (c) 2005-2007 Stu Nicholls. All rights reserved.
    // The original version of this script and the associated (x)html is available at http://www.stunicholls.com/menu/tree_frog_vertical.html
    // This script and the associated (x)html may be modified inany way to fit your requirements.
    // This copyright notice must be untouched at all times.
    -->
        var ClickMenu = function(menu) {
            var getEls = document.getElementById(menu).getElementsByTagName('LI');
            var getAgn = getEls;
            for (var i = 0; i < getEls.length; i++) {
                getEls[i].onclick = function() {
                    for (var x = 0; x < getAgn.length; x++) {
                        getAgn[x].className = getAgn[x].className.replace('unclick', '');
                        getAgn[x].className = getAgn[x].className.replace('click', 'unclick');
                    }
                    if ((this.className.indexOf('unclick')) != -1)
                         this.className = this.className.replace('unclick', '');
                    else this.className += ' click';
                };
                getEls[i].onmouseover = function() { this.className += ' hover'; };
                getEls[i].onmouseout  = function() { this.className = this.className.replace('hover', ''); };
            }
        };
    </script>
</head>
<body onload="ClickMenu('menu')">
<div class="page-title">[__Navigation]</div>
<div>
    <ul id="menu">
        <li class="sub">&#0187; [__Return to] ...
            <ul>
                <li class="popup">
                    <a href="{ROOT}" target="_top">... [__site index]</a>
                    <ul>
                    [each=menu]
                        <li><a href="{menu[link]}" target="_top">[if=menu[icon]]<img src="{menu[icon]}" width="16" height="16" alt="" />[/if]{menu[name]}</a></li>
                    [/each.menu]
                    </ul>
                </li>
                <li><a href="{MODULE}admin" target="main"> ... [__admin index]</a></li>
            </ul>
        </li>
        [each=modules]
            [if=modules[module]]
                <li class="sub">
                    {modules[name]}
                    <ul>
                    [each=modules[module]]
                        <li><a href="{MODULE}admin&amp;id={module[category]}.{module[module]}" target="main">{module[title]}</a></li>
                    [/each.modules[module]]
                    </ul>
                </li>
            [/if]
            [if=nomodule]<li>&#0187; <a href="{MODULE}admin&amp;id={module[category]}.index';?>" target="main" class="th">{module[name]}</a></li>[/if]
        [/each.modules]
    </ul>
</div>
</body>
</html>
