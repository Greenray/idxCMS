<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# LAST ITEMS TEMPLATE

die();?>
<div class="last-posts">
    <ul>
        [each=items]
            <li>
                <div class="center"><a href="{items[link]}"><img src="{items[path]}{items[id]}{DS}{items[image]}.jpg" width="200" height="150" /></a></div>
                <div class="center">{items[title]}</div>
            </li>
        [endeach.items]
    </ul>
</div>
