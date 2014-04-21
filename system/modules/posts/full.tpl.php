<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POSTS - FULL POST TEMPLATE

die();?>
<div class="post">
    <div class="info">
        [if=rateid]<div id="rate{rateid}">{rate}</div>[endif]
        <div class="date">[__Category]: <a href="{MODULE}posts{SECTION}{section}{CATEGORY}{category}">{category_title}</a></div>
        <span class="date">{date}</span>
    </div>
    <div class="title"><h1>{title}</h1></div>
    <div class="text">{text}</div>
    <div class="info">
        <hr />
        <a href="{MODULE}posts.print{SECTION}{section}{CATEGORY}{category}{ITEM}{id}" target="_blank">
            <img src="{ICONS}printer.png" width="16" height="16" hspace="5" vspace="5" class="tip" alt="[__Version for printer]" />
        </a>
        <span class="author">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
        [if=admin]
            <span class="admin">
                <a href="{MODULE}posts{SECTION}{section}{CATEGORY}{category}{ITEM}{id}&amp;action={action}">
                    <img src="{ICONS}{action}.png" width="16" height="16" class="tip" alt="{command}" />
                </a>
            </span>
            <span class="admin">
                <a href="{MODULE}posts.post{SECTION}{section}{CATEGORY}{category}{ITEM}{id}&amp;edit=1">[__Edit]</a>
            </span>
        [endif]
    </div>
</div>
