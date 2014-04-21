<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE CATALOGS - SHORT IMAGE DESCRIPTION TEMPLATE

die();?>
<div class="gallery">
    [each=images]
        <span class="item">
            [if=images[rateid]]<div id="rate{images[rateid]}" style="margin:0px 0 10px;padding:0 0 10px;">{images[rate]}</div>[endif]
            <a class="cbox" href="{images[path]}{images[id]}{DS}{images[image]}" title="{images[title]}">
                <img src="{images[path]}{images[id]}{DS}{images[image]}.jpg" width="{images[width]}" height="{images[height]}" hspace="10" vspace="10" alt="" />
            </a>
            <div class="title">{images[title]}</div>
            <div class="info">
                <a href="{images[link]}">[__Read more...] [if=images[views]]{images[views]}[endif]</a>
                <a href="{comment}">[__Comments] [if=comments][{comments}][endif]</a>
            </div>
        </span>
    [endeach.images]
</div>
<div class="clear"></div>
