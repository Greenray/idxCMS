<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# LAST ITEMS TEMPLATE

die();?>
<div class="last-posts">
    <ul>
    [each=items]
        <li>
            <small>{items[date]}</small><br />
            <a href="{items[link]}">{items[title]}</a><br />
            <span class="info">
                [__Views]: {items[views]}
                [if=items[comments]][__Comments]: <a href="{items[link]}{COMMENT}{items[comments]}">{items[comments]}</a>[endif]
            </span>
        </li>
        <hr>
    [endeach.items]
    </ul>
</div>
