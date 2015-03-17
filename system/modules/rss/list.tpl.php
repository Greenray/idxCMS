<?php
# idxCMS Flat Files Content Management Sysytem
# Module RSS
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div id="section">
    <ul class="level1">
        [each=feed]
            <li class="level1 parent">
                <div class="bg"><a class="level1" href="{MODULE}rss&amp;feed={feed[module]}"><span class="title">{feed[feed]}</span> </a></div>
                <div class="sub">
                     <ul class="level2">
                        [each=feed[categories]]
                            <li class="level2">
                                <img src="{categories[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                                <span class="title">{categories[title]}</span>
                                <span class="subtitle">{categories[desc]}</span>
                            </li>
                        [endeach.feed[categories]]
                    </ul>
                </div>
                <hr />
            </li>
        [endeach.feed]
    </ul>
</div>
