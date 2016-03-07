<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module MENU: Simple menu template

die();?>

<div class="simple-menu">
    <ul class="center">
    <!-- FOREACH menu = $menus -->
        <li><a href="$menu.link" title="$menu.desc">$menu.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
</div>
