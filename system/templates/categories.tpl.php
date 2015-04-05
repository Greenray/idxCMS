<?php
# idxCMS version 2.4
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# CATEGORIES TEMPLATE

die();?>
<div id="section">
    <ul class="level1">
        [each=categories]
            <li class="level1 parent">
                <div class="bg">
                    <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                    <a class="level1" href="{categories[link]}">
                        <span class="title">{categories[title]}</span>
                        <span class="subtitle">{categories[desc]}</span>
                    </a>
                </div>
                [if=categories[last]]
                    <div class="sub">
                        <ul class="level2">
                            <li class="level2">
                                <a class="level2" href="{categories[last][link]}">
                                    <span style="float:left;margin:0 5px 0 0;">[__Last addition]: </span>
                                    <span class="title">{categories[last][title]}</span>
                                </a>
                                <span class="subtitle">[__Total]: {categories[items]}</span>
                            </li>
                        </ul>
                    </div>
                [endif]
            </li>
        [endeach.categories]
    </ul>
</div>
