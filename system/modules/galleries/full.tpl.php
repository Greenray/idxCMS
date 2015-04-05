<?php
# idxCMS Flat Files Content Management Sysytem
# Module Galleries
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="post">
    <div class="info">
        [if=rateid]<div id="rate{rateid}">{rate}</div>[/if]
        <div class="date">[__Category]: <a href="{MODULE}galleries{SECTION}{section}{CATEGORY}{category}">{category_title}</a></div>
        <span class="date">{date}</span>
    </div>
    <div class="title"><h1>{title}</h1></div>
    <div class="text justify">
        <a class="cbox" href="{GALLERIES}{section}{DS}{category}{DS}{id}{DS}{image}" title="{title}">
            <img src="{GALLERIES}{section}{DS}{category}{DS}{id}{DS}{image}.jpg" width="{width}" height="{height}" hspace="10" vspace="10" alt="" />
        </a>
        {text}
    </div>
    <div class="info">
        <span class="author center">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
        <span class="admin">[__Copyright]: {copyright}</span>
    </div>
</div>
