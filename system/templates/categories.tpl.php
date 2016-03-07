<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Template for categories

die();?>

<div class="section">
    <ul class="level3">
    <!-- FOREACH category = $categories -->
        <li class="level3">
            <img src="[$category.path:]icon.png" width="35" height="35" alt="ICON" />
            <a class="level3" href="$category.link">
                <span class="title">$category.title</span>
            <!-- IF !empty($category.desc) -->
                <span class="subtitle">$category.desc</span>
            <!-- ENDIF -->
            </a>
            <!-- IF !empty($category.last_id) -->
                <ul>
                    <li>
                        <span style="float:left;margin:0 5px 0 0;">__The latest publication__: </span>
                        <a href="$category.link{ITEM}$category.last_id">
                            <span class="title">$category.last_title</span>
                        </a>
                    </li>
                </ul>
            <!-- ENDIF -->
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
