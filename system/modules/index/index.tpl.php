<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module INDEX: Template for default main page

die();?>

<!-- FOREACH post = $posts -->
    <div class="tab_content">
        <div class="date">$post.date</div>
        <img src="[$icon:]icon.png" width="35" height="35" alt="ICON" />
        <div class="section">__Section__: <a href="$post.section_link">$post.section_title</a></div>
        <div class="category">__Category__: <a href="$post.category_link">$post.category_title</a></div>
        <div class="title"><h1>$post.title</h1></div>
        <div class="text justify">$post.desc</div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$post.author">$post.nick</a></span>
            <span class="more">
                <a href="$post.link">__Read more...__ <!-- IF !empty($post.views) -->[$post.views]<!-- ENDIF --></a>&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- IF !empty($post.comment) -->
                    <a href="$post.comment">__Comments__ [$post.comments]</a>
                <!-- ELSE -->
                    <a href="$post.link">__Comments__</a>
                <!-- ENDIF -->
            </span>
        </div>
    </div>
<!-- ENDFOREACH -->
