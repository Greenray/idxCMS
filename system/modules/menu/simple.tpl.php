<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div id="simple-menu">
    <ul class="center">
    [each=menu]
        <li><a href="{menu[link]}" title="{menu[desc]}">{menu[name]}</a></li>
    [/each/menu]
    </ul>
</div>
