<?php
# idxCMS Flat Files Content Management Sysytem
# Module Galleries
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="gallery">
    [each=images]
        <span class="item">
            [if=images[rateid]]<div id="rate{images[rateid]}">{images[rate]}</div>[endif]
            <a class="cbox" href="{images[path]}{images[id]}{DS}{images[image]}" title="{images[title]}">
                <img src="{images[path]}{images[id]}{DS}{images[image]}.jpg" width="{images[width]}" height="{images[height]}" hspace="10" vspace="10" alt="" />
            </a>
            <div class="title">{images[title]}</div>
            <div class="info">
                <a href="{images[link]}">[__Read more...] [if=images[views]]{images[views]}[endif]</a>
                <a href="{images[comment]}">[__Comments] [if=comments][{comments}][endif]</a>
            </div>
        </span>
    [endeach.images]
</div>
<div class="clear"></div>
