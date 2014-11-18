<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE MENU - TEMPLATE

die();?>

<li>
    <a href="{link}">{name}</a>
    [ifelse=sections]
        <ul>
            [each=sections]
                <li><a href="{sections[link]}" style="width:{sections[width]}px">{sections[title]}</a></li>
            [endeach.sections]
        </ul>
    [else]
        [if=categories]
            <ul>
                [each=categories]
                    <li><a href="{categories[link]}">{categories[title]}</a></li>
                [endeach.categories]
            </ul>
        [endif]
    [endelse]
</li>
