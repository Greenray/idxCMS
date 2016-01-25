<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module RSS: List template

die();?>

<div class="section">
    <ul class="level1">
    <!-- FOREACH feed = $feeds -->
        <li class="level1 parent">
            <div class="bg"><a class="level1" href="{MODULE}rss&feed=$feed.module"><span class="title">$feed.feed</span> </a></div>
            <div class="sub">
                <ul class="level2">
                <!-- FOREACH category = $feed.categories -->
                    <li class="level2">
                        <img src="[$category.path:]icon.png" width="35" height="35" hspace="10" alt="" />
                        <span class="title">$category.title</span>
                        <span class="subtitle">$category.desc</span>
                    </li>
                <!-- ENDFOREACH -->
                </ul>
            </div>
            <hr />
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
