<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>

<!DOCTYPE html>
<head>
    <title><?php echo __('Navigation');?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <style type="text/css">
        <!--
        body { background: #006699; color: yellow; font: normal 10pt arial, helvetica, sans, sans-serif; text-shadow: 1px 1px 1px black }
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
                         this.className = this.className.replace('unclick', '');;
                    else this.className += ' click';
                };
                getEls[i].onmouseover=function()  this.className += ' hover';
                getEls[i].onmouseout = function() this.className = this.className.replace('hover', '');
            }
        };
    </script>
</head>
<body onload="ClickMenu('menu')">
<div class="page-title"><?php echo __('Navigation');?></div>
<div>
    <ul id="menu">
        <li class="sub">&#0187; <?php echo __('Return to');?> ...
            <ul>
                <li class="popup">
                    <a href="<?php echo ROOT;?>" target="_top">... <?php echo __('site index');?></a>
                    <ul>
                        <?php
                        $menu = '';
                        $navigation = GetUnserialized(CONTENT.'menu');
                        foreach ($navigation as $k => $item) {
                            if (!empty($item['icon']))
                                 $menu .= '<li><a href="'.$item['link'].'" target="_top"><img src="'.$item['icon'].'" width="16" height="16" alt="" />'.$item['name'].'</a></li>';
                            else $menu .= '<li><a href="'.$item['link'].'" target="_top">'.$item['name'].'</a></li>';
                        }
                        echo $menu;?>
                    </ul>
                </li>
                <li><a href="<?php echo MODULE.'admin';?>" target="main"> ... <?php echo __('admin index');?></a></li>
            </ul>
        </li>
        <?php
        foreach($MODULES as $category => $data) {
            if (!empty($data[1])) {
                if (is_array($data[1])) { ?>
                    <li class="sub">
                        <?php echo $data[0];?>
                        <ul>
                            <?php foreach($data[1] as $module => $title) { ?>
                                    <li><a href="<?php echo MODULE.'admin&amp;id='.$category.'.'.$module;?>" target="main"><?php echo $title;?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
          <?php } elseif ($data[0] === $data[1]) { ?>
                    <li>&#0187; <a href="<?php echo MODULE.'admin&amp;id='.$category.'.index';?>" target="main" class="th"><?php echo $data[0];?></a></li>
          <?php }
            }
        } ?>
    </ul>
</div>
</body>
</html>
