<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module RSS: List template

die();?>

<div class="section">
    <ul class="level1">
    <!-- FOREACH feed = $feeds -->
        <li class="level1 parent">
            <div class="bg"><a class="level1" href="{MODULE}rss&amp;feed=$feed.module"><span class="title">$feed.feed</span> </a></div>
            <div class="sub">
                <ul class="level2">
                <!-- FOREACH category = $feed.categories -->
                    <li class="level2">
                        <img src="[$category.path:]icon.png" width="35" height="35" alt="ICON" />
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
