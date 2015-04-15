<?php
# idxCMS Flat Files Content Management Sysytem
# Module Sitemap
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<li class="level1 parent">
    <a class="level1 parent" href="{link}">
        <span class="bg">
            [ifelse=desc]
                <span class="title">{name}</span>
                <span class="subtitle">{desc}</span>
            [else]
                {name}
            [/else]
        </span>
    </a>
    [ifelse=sections]
        <div class="dropdown">
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
                                    [/each.sections[categories]]
                                </ul>
                            </div>
                        [/if]
                    </li>
                [/each.sections]
            </ul>
        </div>
    [else]
        [if=categories]
            <div class="dropdown">
                <ul class="level3">
                    [each=categories]
                        <li class="level3">
                            <a class="level3" href="{categories[link]}">
                                <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                                <span class="title">{categories[title]}</span>
                                <span class="subtitle">{categories[desc]}</span>
                            </a>
                        </li>
                    [/each.categories]
                </ul>
            </div>
        [/if]
    [/else]
</li>
