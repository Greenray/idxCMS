<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE INDEX - DEFAULT TEMPLATE

die();?>
<div class="title"><a href="{category[link]}">{category[title]}</a></div>
<div id="tabs-{tab}">
    <div class="tabs">
        <ul class="tabs">
            [each=posts]
                <li><a class="" href="#{posts[tab]}">{posts[tab_date]}</a></li>
            [endeach.posts]
        </ul>
        [each=posts]
            <div id="{posts[tab]}" class="tab_content">
                <div class="date">{posts[date]}</div>
                <img src="{category[path]}icon.png" width="35" height="35" hspace="10" alt="" />
                <div class="section">
                    [__Section]: <a href="{section[link]}">{section[title]}</a>
                </div>
                <div class="category">
                    [__Category]: <a href="{category[link]}">{category[title]}</a>
                </div>
                <div class="title"><h1>{posts[title]}</h1></div>
                <div class="text">{posts[desc]}</div>
                <div class="info">
                    <span class="author">[__Posted by]: <a href="{MODULE}user&amp;user={posts[author]}">{posts[nick]}</a></span>
                    <span class="more">
                        <a href="{posts[link]}">[__Read more...] [if=posts[views]][{posts[views]}][endif]</a>
                        [ifelse=posts[comment]]
                            <a href="{posts[comment]}">[__Comments] [{posts[comments]}]</a>
                        [else]
                            <a href="{posts[link]}">[__Comments]</a>
                        [endelse]
                    </span>
                </div>
            </div>
        [endeach.posts]
    </div>
</div>
<div class="clear"></div>