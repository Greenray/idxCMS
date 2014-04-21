<?php
# idxCMS Development vesion
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE GALLERIES - RANDOM IMAGE TEMPLATE

die();?>
[each=random]
    <div class="center"><a href="{random[link]}"><img src="{random[path]}{random[id]}{DS}{random[image]}.jpg" width="200" height="150" /></a></div>
    <div class="center">{random[title]}</div>
[endeach.random]