<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="post">
    <div class="info">
        [if=rateid]<div id="rate{rateid}">{rate}</div>[endif]
        <div class="date">[__Category]: <a href="{MODULE}posts{SECTION}{section}{CATEGORY}{category}">{category_title}</a></div>
        <span class="date">{date}</span>
    </div>
    <div class="title"><h1>{title}</h1></div>
    <div class="text justify">{text}</div>
    <div class="info">
        <hr />
        <a href="{MODULE}posts.print{SECTION}{section}{CATEGORY}{category}{ITEM}{id}" target="_blank">
            <img src="{ICONS}printer.png" width="16" height="16" hspace="5" vspace="5" class="tip" alt="[__Version for printer]" />
        </a>
        <span class="author center">[__Author]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
        [if=admin]
            <div class="menu">
                <form name="post" method="post" action="">
                    <button formaction="{MODULE}posts{SECTION}{section}{CATEGORY}{category}{ITEM}{id}&amp;action={action}" class="submit">[__Edit]</button>
                    <button formaction="{command}" class="submit">[__Close]</button>
                </form>
            </div>
        [endif]
    </div>
</div>
