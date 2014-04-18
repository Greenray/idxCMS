<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE MENU - TEMPLATE

die();?>
<li class="level1 {class} parent [if=active]{active}[endif]">
    <a class="level1 {class} parent [if=active]{active}[endif]" href="{link}">
        <span class="bg">
            [ifelse=desc]
                <span class="title">{name}</span>
                <span class="subtitle">{desc}</span>
            [else]
                {name}
            [endelse]
        </span>
    </a>
    [ifelse=sections]
        <div class="dropdown" style="width:{width}px;">
            <ul class="level2">
                [each=sections]
                    <li class="level2 parent">
                        <div class="bg">
                            <a class="level2" href="{sections[link]}">
                                <span class="title">{sections[title]}</span>
                                <span class="subtitle">{sections[desc]}</span>
                            </a>
                        </div>
                        [if=sections[categories]]
                            <div class="sub">
                                <ul class="level3">
                                    [each=sections[categories]]
                                        <li class="level3">
                                            <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                                            <a class="level3" href="{categories[link]}">
                                                <span class="title">{categories[title]}</span>
                                                <span class="subtitle">{categories[desc]}</span>
                                            </a>
                                        </li>
                                    [endeach.sections[categories]]
                                </ul>
                            </div>
                        [endif]
                    </li>
                [endeach.sections]
            </ul>
        </div>
    [else]
        [if=categories]
            <div class="dropdown" style="width:{width}px;">
                <ul class="level3">
                    [each=categories]
                        <li class="level3">
                            <a class="level3" href="{categories[link]}">
                                <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                                <span class="title">{categories[title]}</span>
                                <span class="subtitle">{categories[desc]}</span>
                            </a>
                        </li>
                    [endeach.categories]
                </ul>
            </div>
        [endif]
    [endelse]
</li>
