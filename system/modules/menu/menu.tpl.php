<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module MENU: Main menu template

die();?>

<div class="main-menu center">
    <ul class="menu">
    <!-- FOREACH menu = $menu -->
        <li>
            <a href="$menu.link">$menu.name</a>
            <!-- IF !empty($menu.sections) -->
                <ul>
                <!-- FOREACH section = $menu.sections -->
                    <li><a href="$section.link" style="width:[$section.width:]px">$section.title</a></li>
                <!-- ENDFOREACH -->
                </ul>
            <!-- ENDIF -->
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
