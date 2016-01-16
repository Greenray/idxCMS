<?php
# idxCMS Flat Files Content Management System
# Version 3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module "Index" - Template for default main page

die();?>

<div id="tabs-$tab">
    <div class="tabs">
        <ul class="tabs">
        <!-- FOREACH post = $posts -->
            <li><a class="roll-link" href="#$post.tab"><span data-title="$post.tab_date">$post.tab_date</span></a></li>
        <!-- ENDFOREACH -->
        </ul>
        <div class="tab_container">
        <!-- FOREACH post = $posts -->
            <div id="$post.tab" class="tab_content">
                <div class="date">$post.date</div>
                <img src="[$icon:]icon.png" width="35" height="35" alt="" />
                <div class="section">__Section__: <a href="$post.section_link">$post.section_title</a></div>
                <div class="category">__Category__: <a href="$post.category_link">$post.category_title</a></div>
                <div class="title"><h1>$post.title</h1></div>
                <div class="text justify">$post.desc</div>
                <div class="info">
                    <span class="author center">__Posted by__: <a class="roll-link" href="{MODULE}user&amp;user=$post.author"><span data-title="$post.nick">$post.nick</span></a></span>
                    <span class="more">
                        <a class="roll-link" href="$post.link"><span data-title="__Read more...__">__Read more...__</span></a><!-- IF !empty($post.views) -->&#x3014;$post.views&#x3015;<!-- ENDIF -->
                        <!-- IF !empty($post.comment) -->
                            <a class="roll-link" href="$post.comment"><span data-title="__Comments__">__Comments__</span></a>&#x3014;$post.comments&#x3015;
                        <!-- ELSE -->
                            <a class="roll-link" href="$post.link"><span data-title="__Comments__">__Comments__</span></a>
                        <!-- ENDIF -->
                    </span>
                </div>
            </div>
        <!-- ENDFOREACH -->
        </div>
    </div>
</div>
