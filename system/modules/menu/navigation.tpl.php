<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE NAVIGATION - TEMPLATE

die();?>
<ul class="links">
    [each=points]
        <li><img src="{points[icon]}" width="16" height="16" alt="" /><a href="{points[link]}">{points[name]}</a></li>
    [endeach.points]
</ul>
