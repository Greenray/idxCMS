<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<li>
    <a href="{link}">{name}</a>
    [ifelse=sections]
        <ul>
        [each=sections]<li><a href="{sections[link]}" style="width:{sections[width]}px">{sections[title]}</a></li>[endeach.sections]
        </ul>
    [else]
        [if=categories]
            <ul>
            [each=categories]<li><a href="{categories[link]}">{categories[title]}</a></li>[endeach.categories]
            </ul>
        [endif]
    [endelse]
</li>
