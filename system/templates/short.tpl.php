<?php
# idxCMS version   2.4
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# SHOT ITEM DESCRIPTION TEMPLATE

die();?>
<div class="post">
    <div class="date">{date}</div>
    [if=rateid]<div id="rate{rateid}" class="main_rate">{rate}</div>[/if]
    <div class="title"><h1><a href="{link}">{title}</a></h1></div>
    <div class="text justify">{desc}</div>
    <div class="info">
        <span class="author center">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
        <span class="admin">
            <a href="{link}">[__Read more...] [if=views][{views}][/if]</a>
            [if=downloads][__Downloads] [{downloads}][/if]
            [if=visits][__Transitions] [{visits}][/if]
            <a href="{comment}">[__Comments] [if=comments][{comments}][/if]</a>
        </span>
    </div>
</div>
