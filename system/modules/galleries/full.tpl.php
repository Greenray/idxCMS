<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE CATALOGS - FULL ITEM TEMPLATE

die();?>
<div class="post">
    <div class="info">
        [if=rateid]<div id="rate{rateid}">{rate}</div>[endif]
        <div class="date">[__Category]: <a href="{MODULE}galleries{SECTION}{section}{CATEGORY}{category}">{category_title}</a></div>
        <span class="date">{date}</span>
    </div>
    <div class="title"><h1>{title}</h1></div>
    <div class="text">
        <a class="cbox" href="{GALLERIES}{section}{DS}{category}{DS}{id}{DS}{image}" title="{title}">
            <img src="{GALLERIES}{section}{DS}{category}{DS}{id}{DS}{image}.jpg" width="{width}" height="{height}" hspace="10" vspace="10" alt="" />
        </a>
        {text}
    </div>
    <div class="info">
        <span class="author">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
        <span class="admin">[__Copyright]: &copy; {copyright}</span>
    </div>
</div>
