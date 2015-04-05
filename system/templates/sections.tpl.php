<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# SECTIONS TEMPLATE

die();?>

<div id="section">
    <ul class="level1">
    [each=sections]
        <li class="level1 parent">
            <div class="bg">
                <a class="level1" href="{sections[link]}">
                    <span class="title">{sections[title]}</span>
                    <span class="subtitle">{sections[desc]}</span>
                </a>
            </div>
            [if=sections[categories]]
                <div class="sub">
                    <ul class="level2">
                    [each=sections[categories]]
                        <li class="level2">
                            <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                            <a class="level2" href="{categories[link]}">
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
